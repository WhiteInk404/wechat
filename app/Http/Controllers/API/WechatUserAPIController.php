<?php

namespace App\Http\Controllers\API;

use App\Entities\WechatUser;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\WechatUserAuthRequest;
use App\User;
use Cache;
use DB;
use EasyWeChat\Core\Exceptions\HttpException;
use EasyWeChat\Foundation\Application;
use Illuminate\Database\QueryException;
use JWTAuth;
use Log;

class WechatUserAPIController extends AppBaseController
{
    private $wechat_app;

    public function __construct(Application $wechat_app)
    {
        $this->wechat_app = $wechat_app;
    }

    /**
     * 小程序换取 jwt token，如果用户信息不存在时，新建该用户
     *
     * @param \App\Http\Requests\API\WechatUserAuthRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function auth(WechatUserAuthRequest $request)
    {
        $input = $request->only(['js_code', 'iv', 'encrypted_data']);
        try {
            $session_key_result = Cache::get($input['js_code']);
            if (!$session_key_result) {
                $session_key_result = $this->wechat_app->mini_program->sns->getSessionKey($input['js_code']);
                Cache::add($input['js_code'], $session_key_result, 5);
            }
        } catch (HttpException $e) {
            $error_msg = 'js_code[' . $input['js_code'] . '] 换 session key 时出错';
            Log::error($error_msg, ['exception_msg' => $e->getMessage()]);

            return $this->sendError(['exception_msg' => $e->getMessage()], $error_msg);
        }
        $openid      = $session_key_result->get('openid');
        $session_key = $session_key_result->get('session_key');

        /** @var \App\Entities\WechatUser $wechat_app_user */
        $wechat_app_user = WechatUser::where('openid', $openid)->first();

        if (!$wechat_app_user) {
            DB::beginTransaction();
            try {
                $user            = $this->createBaseUser($openid);
                $wechat_app_user = WechatUser::create(['user_id' => $user->id, 'openid' => $openid]);
            } catch (QueryException $e) {
                DB::rollBack();
                $error_msg = 'auth 数据库存储用户时发生了些错误';
                Log::error($error_msg, ['exception_msg' => $e->getMessage()]);

                return $this->sendError(['exception_msg' => $e->getMessage()], $error_msg);
            }
            DB::commit();
            // 解密用户详细信息，并修改已保存用户信息
            $decrypted_data = $this->wechat_app->mini_program->encryptor->decryptData($session_key, $input['iv'], $input['encrypted_data']);
            if ($decrypted_data) {
                // 填充微信用户信息
                WechatUser::where('openid', $openid)->update([
                    'nickname'   => array_get($decrypted_data, 'nickName'),
                    'gender'     => array_get($decrypted_data, 'gender'),
                    'city'       => array_get($decrypted_data, 'city'),
                    'province'   => array_get($decrypted_data, 'province'),
                    'country'    => array_get($decrypted_data, 'country'),
                    'avatar_url' => array_get($decrypted_data, 'avatarUrl'),
                    'union_id'   => array_get($decrypted_data, 'unionId', ''),
                ]);

                // 修改用户表中对应用户的 name，创建时name 默认值是用户的 openid
                User::where('name', $openid)->update(['name' => array_get($decrypted_data, 'nickName')]);
            }
        }

        $auth_token = JWTAuth::fromUser($wechat_app_user->user);

        return $this->sendResponse(['auth_token' => $auth_token], '获取 token 成功');
    }

    /**
     * 创建小程序用户信息之前先创建基础用户信息数据
     *
     * @param $openid
     *
     * @return User
     */
    private function createBaseUser($openid)
    {
        return User::create(['name' => $openid, 'email' => $openid . '@' . 'wechat.app', 'password' => bcrypt($openid), 'passport_type' => User::PASSPORT_TYPE_WXA]);
    }
}
