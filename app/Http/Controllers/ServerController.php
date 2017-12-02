<?php

namespace App\Http\Controllers;

use App\Entities\Activity;
use App\Entities\Participant;
use App\Entities\Team;
use App\Entities\WechatUser;
use App\Jobs\MakeActivityQr;
use App\Jobs\SendStaffMessage;
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
                //1.处理事件类型消息
                case 'event':
                    switch ($message->Event) {
                        //1.1 处理订阅事件类型消息
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
                        //1.2 处理扫描事件类型消息
                        case 'SCAN':
                            $this->getUser($open_id);
                            if ($message->EventKey == 'plz_remind_me') {
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
                //2.处理文本类型消息
                //
                case 'text':
                //预先设置两个变量 $activity、$team_name 的值为 NULL，另外 $activity_name 默认不存在
                //用设置好的正则表达式匹配用户的TEXT文本信息
                //    匹配结果只有两种：
                //          1. count($matches)==0，不可能是新建队伍；
                //          2. count($matches)==3，可能是新建队伍。
                //下面再通过 $activity、$team_name 值的变化处理用户请求
                    $wechat_user = $this->getUser($open_id);
                    $activity    = '';
                    $team_name   = '';
                    preg_match('/(.*)#(.*)#/', $message->Content, $matches);
                    //关键词中有两个#的才满足正则匹配，count($matches)==3，否则count($matches)==0，对于count($matches)==3的情况，也即关键词中有2个#，有如下4中情况：
                    //  1#1#  1#0#  0#1#  0#0#，下面将会分别进行判断
                    if (count($matches) > 2) {
                      //count($matches)==3 的情况，也即关键词中有2个#
                        if (isset($matches[1]) && !empty($matches[1]) && isset($matches[2]) && !empty($matches[2])) {
                            //1#1#，用户的目的是，按照活动说明新建团队
                            //这种情况下，$activity、$team_name 的值由最初的 NULL 变成非 NULL
                            $team_name     = $matches[1];
                            $activity_name = $matches[2];
                            //判断$activity_name是否存在于Activity表中
                            //如果存在，说明该用户参与的活动存在且没过期，如果不存在，说明该活动不存在或已过期
                            $activity = Activity::whereName($activity_name)->first();
                        }
                        //后三种情况：1#0#  0#1#  0#0#
                        //此处不做处理
                    } else {
                        //count($matches)==0 的情况，这种情况下用户不是新建团队，但是有可能是查询团队情况，或者，获取团队海报，如果这两者都不是，那么可以确定这个关键词和海报活动无关

                        //判断用户的关键词$message->Content是否存在于Activity表中，即判断是否在查询团队情况
                        $activity = Activity::whereName($message->Content)->first();
                    }
                    //经过上面的判断 $activity、$team_name、$activity_name 的状态和值发生了变化
                    //⚠️注意：只有1#1#这种情况下，$team_name、$activity_name 才有意义

                    if ($activity) {
                        //Activity表中查询到了，说明用户的关键词中包含或者就是有效的活动名
                        if ($team_name) {
                        //如果$team_name也存在，说明是1#1#的情况，该情况是用户要新创建团队
                            //查询团队名是否已存在
                            $team = Team::whereName($team_name)->whereActivityId($activity->id)->first();

                            //该团队名已存在
                            if ($team) {
                                //🍉提示用户更换团队名
                                return '"' . $activity->name . '"活动中团队名 ' . $team_name . ' 已经存在，请换一个';
                            }

                            //该团队名不存在
                            //判断该用户在此活动中是否已经创建过团队
                            $team = Team::whereUserId($wechat_user->user_id)->whereActivityId($activity->id)->first();

                            //已经有团队
                            if ($team) {
                                //🍉提示用户已创建过团队，不能再创建了
                                return '您在"' . $activity->name . '"活动中已经创建过团队 ' . $team->name . ' 了';
                            }

                            //没有创建过，且团队名可以使用
                            //🍉创建团队，并提示用户团队创建成功
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

                        } else {
                          //$team_name不存在，不过，$activity==1，说明用户不有可能是查询团队情况
                            //根据活动名和用户id在Team（创建）表中查询
                            $team = Team::whereActivityId($activity->id)->whereUserId($wechat_user->user_id)->first();

                            // 有创建
                            //🍉返回团队排名情况
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
                                //没有创建
                                //再查询是否有支持的团队
                                $participant = Participant::whereActivityId($activity->id)->whereUserId($wechat_user->user_id)->first();
                                // 有支持
                                //🍉返回支持团队的排名情况
                                if ($participant) {
                                    $sort = Team::whereActivityId($activity->id)->where('count', '>', $participant->team->count)->count() + 1;
                                    $msg  = <<<EOL
感谢您参与“ $activity->name ”活动。

您目前支持的团队情况如下：
团队名称：{$participant->team->name}
支持人数：{$participant->team->count}
当前排名：$sort
您也可以创建自己的团队，赢取活动资格。回复团队名称+#{$activity->name}#，如：南京大学自2班#{$activity->name}#，即可创建自己的团队。
活动详情请点击 www.wecee.com 查看。
EOL;
                                } else {
                                  //没有创建且没有支持
                                  //🍉提示用户没有参加该活动
                                    $msg = <<<EOL
感谢您参与“{$activity->name}”活动，目前您还没有创建自己的团队，回复团队名称+#{$activity->name}#，如：南京大学自2班#{$activity->name}#，即可创建自己的团队。
活动详情请点击 www.wecee.com 查看。
EOL;
                                }
                            }

                            return $msg;
                        }
                    } else {
                    //$activity 为 NULL
                    //如果与活动有关的话，只可能是通过团队名获取团队海报
                        $team_name = $message->Content;
                        //查询该团队存不存在
                        $team      = Team::whereName($team_name)->first();
                        if ($team) {
                            //存在该团队
                            //🍉返回团队海报
                            $this->dispatch(new MakeActivityQr($wechat_user, $team->activity, $team));
                        } else {
                            //不存在该团队
                            //🚩普通关键词回复

                            if ($message->Content=='7000') {
                                $msg = <<<EOL
链接: https://pan.baidu.com/s/1jIl4nMu
密码: xfqf
EOL;
//                            $flag = $message->Content;
                            //用客服消息发送第二条消息
                        //    $this->dispatch(new SendStaffMessage($wechat_user));
                        $team_name = '淘老外';
                        $team      = Team::whereName($team_name)->first();
                        $this->dispatch(new MakeActivityQr($wechat_user, $team->activity, $team));

                            }elseif ($message->Content=='十六字训练秘诀') {
                              $msg = '十六字训练秘诀';

                            }elseif ($message->Content=='移植') {
                              $msg = <<<EOL
链接: https://pan.baidu.com/s/1slmK5uH
密码: w852
EOL;

                            }else {
                              //机器人聊天
                              $msg = <<<EOL
Hi $wechat_user->nickname
Welcome to WeCee!
客服微信：xuechun_1991
EOL;
                            }

                            //返回自动回复
                            return $msg;

                        }
                    }

                    break;
            }
        });

        return $server->serve();
    }

    private function getWechatUserInfo($openid)
    {
        /** @var \EasyWeChat\User\User $easywechat_user */
        $easywechat_user = EasyWeChat::user();

        /** @var \EasyWeChat\Support\Collection $user_info */
        return $easywechat_user->get($openid);
    }

    private function getUser($open_id)
    {
        $wechat_user = WechatUser::whereOpenid($open_id)->first();
        if (!$wechat_user) {
            $user_info = $this->getWechatUserInfo($open_id);
            $user      = User::create([
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
        } else {
            if (!$wechat_user->union_id) {
                $user_info = $this->getWechatUserInfo($open_id);

                $wechat_user->update([
                    'nickname'   => $user_info->get('nickname'),
                    'avatar_url' => $user_info->get('headimgurl'),
                    'gender'     => $user_info->get('sex'),
                    'city'       => $user_info->get('city'),
                    'province'   => $user_info->get('province'),
                    'country'    => $user_info->get('country'),
                    'union_id'   => $user_info->get('unionid', ''),
                ]);
            }
        }

        return $wechat_user;
    }
}
