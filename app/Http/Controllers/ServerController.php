<?php

namespace App\Http\Controllers;

use App\Entities\Activity;
use App\Entities\Team;
use App\Entities\WechatUser;
use App\User;
use EasyWeChat;
use Log;

class ServerController extends Controller
{
    public function server()
    {
        /** @var \EasyWeChat\Server\Guard $server */
        $server = EasyWeChat::server();

        $server->setMessageHandler(function ($message) {
            $open_id = $message->FromUserName;
            Log::info('wechat server message', [$message]);
            switch ($message->MsgType) {
                case 'event':
                    switch ($message->Event) {
                        case 'subscribe':
                            $wechat_user = $this->getUser($open_id);
                            $str         = <<<EOL
Hi $wechat_user->nickname
Welcome to WeCee!
客服微信：xuechun_1991
EOL;

                            return $str;
                        case 'text':
                            $wechat_user = $this->getUser($open_id);
                            $labels      = mb_substr($message->Content, 0, 1) . ',' . mb_substr($message->Content, -1, 1);
                            $activity    = Activity::whereLabels($labels)->first();
                            if ($activity) {
                                $team_name = mb_substr($message->Content, 1, -1);
                                $team      = Team::whereName($team_name)->whereActivityId($activity->id)->first();
                                if ($team) {
                                    return '团队名 ' . $team_name . ' 已经存在，请更换';
                                }
                                $team = Team::create([
                                    'activity_id' => $activity->id,
                                    'name'        => $team_name,
                                    'user_id'     => $wechat_user->user_id,
                                    'count'       => 0,
                                ]);
                                // 二维码海报？todo
                                $msg  = route('activity_team', ['activity_id' => $activity->id, 'team_id' => $team->id]);
                            } else {
                                $msg = <<<'EOL'
你好，$wechat_user->nickname
如果您需要客服帮助，请添加微信号：
xuechun_1991
EOL;
                            }

                            return $msg;
                            break;
                        default:
                            exit; // 交给机器人吧
                    }
                    break;
            }
        });

        return $server->serve();
    }

    private function getUser($open_id)
    {
        $wechat_user = WechatUser::whereOpenid($open_id)->first();
        if (!$wechat_user) {
            /** @var \EasyWeChat\User\User $easywechat_user */
            $easywechat_user = EasyWeChat::user();
            /** @var \EasyWeChat\Support\Collection $user_info */
            $user_info = $easywechat_user->get($open_id);

            $user = User::create([
                'name'     => $user_info->get('openid'),
                'email'    => $user_info->get('openid') . '@example.com',
                'password' => bcrypt($user_info->get('password')),
            ]);
            $user->wechatUser()->save(new WechatUser([
                    'openid'     => $user_info->get('openid'),
                    'nickname'   => $user_info->get('nickname'),
                    'avatar_url' => $user_info->get('headimgurl'),
                    'gender'     => $user_info->get('sex'),
                    'city'       => $user_info->get('city'),
                    'province'   => $user_info->get('province'),
                    'country'    => $user_info->get('country'),
                    'union_id'   => $user_info->get('unionid', ''),
                ]
            ));

            $wechat_user = $user->wechatUser;
        }

        return $wechat_user;
    }
}
