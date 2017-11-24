<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Http\Controllers\Controller;
use App\Models\WxUser;
use App\Services\WechatSdkService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Oauth2Controller extends Controller
{

    protected $app;
    protected $cid;
    protected $openid;

    private $scopes = ['snsapi_base', 'snsapi_userinfo'];

    /**
     * 微信 Oauth透传模块
     * 参数：redirect_uri scope(snsapi_base、snsapi_userinfo)
     */
    public function handle(Request $request)
    {
        $code = $request->input('code', 0);
        if (!$code) {
            $scope = $request->input('scope');
            if (!in_array($scope, $this->scopes)) {
                return 'scope error';
            }
            $redirectUir = $request->input('redirect_uri');
            if (!$redirectUir) {
                return 'redirect_uri error';
            }
            $httpHost = $request->server('HTTP_HOST');
            $uri = $request->path() . "?href=" . urlencode($redirectUir);
            $oauth = WechatSdkService::init(['callback' => $uri])->oauth;
            return $oauth->scopes([$scope])->redirect();
        } else {
            $href = $request->input('href');
            if (!$href) {
                return false;
            }
            $wxApp = WechatSdkService::init();
            $oauth = $wxApp->oauth;
            $wxOauthUser = $oauth->user();
            $openid = $wxOauthUser->id;
            if ($wxOauthUser->token->scope == 'snsapi_base') {
                $userinfo = ['openid' => $openid];
            } else {
                $userinfo = $wxOauthUser->original;
            }
            $userinfo = urlencode(json_encode($userinfo));
            $wxUser = WxUser::where('openid', $openid)->first();
            if (!$wxUser) {
                $user = new User();
                $user->mobile = '';
                $user->password = '';
                $user->wechat_openid = $openid;
                $user->save();
                $wxUser = new WxUser();
                $wxUser->user_id = $user->id;
                $wxUser->openid = $user->wechat_openid;
            } else {
                $user = User::find($wxUser->user_id);
            }
            // 获取用户是否关注
            $wxAppUser = $wxApp->user->get($openid)->toArray();
            $wxUser->subscribe = $wxAppUser['subscribe'];
            if ($wxAppUser['subscribe']) {
                $nickname = $this->filterEmoji($wxAppUser['nickname']);
                $wxUser->nickname = $nickname;
                $wxUser->sex = $wxAppUser['sex'];
                $wxUser->avatar = $wxAppUser['headimgurl'];
                $wxUser->city = $wxAppUser['city'];
                $wxUser->province = $wxAppUser['province'];
                $wxUser->language = $wxAppUser['language'];
                if (isset($wxAppUser['subscribe_time'])) {
                    $wxUser->subscribe_time = date('Y-m-d H:i:s', $wxAppUser['subscribe_time']);
                }
            } else {
                $wxUser->nickname = '';
                $wxUser->subscribe_time = Carbon::now()->toDateTimeString();
            }
            $wechatCode = $this->createUuid(mt_rand(100,999));
            $wxUser->wechat_code = $wechatCode;
            $wxUser->save();

            if (strpos($href, '?') === false) {
                $redirect = $href . "?wx_code={$wechatCode}";
            } else {
                $redirect = $href . "&wx_code={$wechatCode}";
            }
            return redirect($redirect);
        }
    }

    /**
     * 过滤emoji表情
     * @param $str
     * @return mixed
     */
    private function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }

    private function createUuid($prefix = ""){
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return md5($prefix . $uuid);
    }
}
