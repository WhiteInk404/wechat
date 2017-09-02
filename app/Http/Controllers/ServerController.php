<?php

namespace App\Http\Controllers;

use App\Entities\Activity;
use App\Entities\Participant;
use App\Entities\Team;
use App\Entities\WechatUser;
use App\Jobs\MakeActivityQr;
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
                            if (isset($message->EventKey) && strpos($message->EventKey, 'qrscene_') === 0) {
                                if ($message->EventKey == 'qrscene_plz_remind_me') {
                                    $str = '为您设置每日提醒成功。';
                                } else {
                                    $url = str_replace('qrscene_', '', $message->EventKey);
                                    $str = <<<EOL
<a href="{$url}">请点击此处支持您的团队。</a>
EOL;
                                }

                                return $str;
                            }
                            $str = <<<EOL
Hi $wechat_user->nickname
Welcome to WeCee!
客服微信：xuechun_1991
EOL;

                            return $str;
                        case 'SCAN':
                            $this->getUser($open_id);
                            if ($message->EventKey == 'qrscene_plz_remind_me') {
                                $msg = '为您设置每日提醒成功。';
                            } else {
                                $msg = <<<EOL
<a href="{$message->EventKey}">请点击此处支持您的团队。</a>
EOL;
                            }

                            return $msg;
                            break;
                        default:
                            exit; // 交给机器人吧
                    }
                    break;

                case 'text':
                    $wechat_user = $this->getUser($open_id);
                    $activity    = '';
                    $team_name   = '';
                    preg_match('/(.*)#(.*)#/', $message->Content, $matches);

                    if (count($matches) > 2) {
                        if (isset($matches[1]) && !empty($matches[1]) && isset($matches[2]) && !empty($matches[2])) {
                            $team_name     = $matches[1];
                            $activity_name = $matches[2];

                            $activity = Activity::whereName($activity_name)->first();
                        }
                    } else {
                        $activity = Activity::whereName($message->Content)->first();
                    }

                    if ($activity) {
                        // 创建团队
                        if ($team_name) {
                            $team = Team::whereName($team_name)->whereActivityId($activity->id)->first();
                            if ($team) {
                                return '"' . $activity->name . '"活动中团队名 ' . $team_name . ' 已经存在，请换一个';
                            }

                            $team = Team::whereUserId($wechat_user->user_id)->whereActivityId($activity->id)->first();
                            if ($team) {
                                return '您在"' . $activity->name . '"活动中已经创建过团队 ' . $team->name . ' 了';
                            }
                            $team = Team::create([
                                'activity_id' => $activity->id,
                                'name'        => $team_name,
                                'user_id'     => $wechat_user->user_id,
                                'count'       => 0,
                            ]);

                            $msg = <<<EOL
您的团队已成功创建！分享下面的海报，获取好友支持。
活动截止时间为 $activity->end_time ，我们将根据支持数选出排名前三的 3 个团队免费参加 $activity->name 的活动。
如有问题，请咨询 15251909361。
您可以回复关键词“ $activity->name ”或者扫描海报中的二维码随时查看团队支持情况。
EOL;

                            // 制作二维码，并以客服消息方式返回
                            $this->dispatch(new MakeActivityQr($wechat_user, $activity, $team));

                            return $msg;
                        } else { // 查看支持或创建团队方式
                            $team = Team::whereActivityId($activity->id)->whereUserId($wechat_user->user_id)->first();

                            // 有创建
                            if ($team) {
                                $sort = Team::whereActivityId($activity->id)->where('count', '>', $team->count)->count() + 1;
                                $msg  = <<<EOL
感谢您参与“ $activity->name ”活动。

您的团队支持情况如下：
团队名称：$team->name
支持人数：$team->count
当前排名：$sort
分享海报到朋友圈，让小伙伴们为你的团队助力！
发送团队名称即可获取该团队海报。
EOL;
                            } else {
                                $participant = Participant::whereActivityId($activity->id)->whereUserId($wechat_user->user_id)->first();
                                // 有支持
                                if ($participant) {
                                    $sort = Team::whereActivityId($activity->id)->where('count', '>', $participant->team->count)->count() + 1;
                                    $msg  = <<<EOL
感谢您参与“ $activity->name ”活动。

您目前支持的团队情况如下：
团队名称：{$participant->team->name}
支持人数：{$participant->team->count}
当前排名：$sort
您也可以创建自己的团队，赢取活动资格。回复团队名称+#{$activity->name}#，如：南京大学自2班#中秋免费游#，即可创建自己的团队。
活动详情请点击 www.wecee.com 查看。
EOL;
                                } else { // 无支持
                                    $msg = <<<EOL
感谢您参与“{$activity->name}”活动，目前您还没有创建自己的团队，回复团队名称+#{$activity->name}#，如：南京大学自2班#中秋免费游#，即可创建自己的团队。
活动详情请点击 www.wecee.com 查看。
EOL;
                                }
                            }

                            return $msg;
                        }
                    } else { // 也许是查看团队
                        $team_name = $message->Content;
                        $team      = Team::whereName($team_name)->first();
                        if ($team) {
                            $this->dispatch(new MakeActivityQr($wechat_user, $team->activity, $team));
                        } else {
                            $msg = <<<EOL
你好，$wechat_user->nickname
如果您需要客服帮助，请添加微信号：
xuechun_1991
EOL;

                            return $msg;
                        }
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
                'name'          => $user_info->get('openid'),
                'email'         => $user_info->get('openid') . '@example.com',
                'password'      => bcrypt($user_info->get('password')),
                'passport_type' => User::PASSPORT_TYPE_WECHAT,
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
