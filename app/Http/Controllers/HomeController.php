<?php

namespace App\Http\Controllers;

use App\Entities\Activity;
use App\Entities\Participant;
use App\Entities\Team;
use App\Entities\WechatUser;
use App\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function activityTeam($activity_id, $team_id)
    {
        $activity = Activity::find($activity_id);
        $team     = Team::find($team_id);
        if (!$activity || !$team) {
            return '活动信息有误';
        }

        if (strtotime($activity->end_time) < Carbon::now()->timestamp) {
            return view('activity_team_finished');
        }

        /** @var \Overtrue\Socialite\User $oauth_user */
        $oauth_user = session('wechat.oauth_user');

        $wechat_user = WechatUser::whereOpenid($oauth_user->getId())->first();
        if (!$wechat_user) {
            $user = User::create([
                'name'          => $oauth_user->getId(),
                'passport_type' => User::PASSPORT_TYPE_WECHAT,
                'email'         => $oauth_user->getId() . '@example.com',
                'password'      => bcrypt('password'),
            ]);

            $wechat_user = $user->wechatUser()->save(new WechatUser([
                'openid'     => $oauth_user->getId(),
                'nickname'   => $oauth_user->getNickname(),
                'avatar_url' => $oauth_user->getAvatar(),
                'gender'     => $oauth_user->getOriginal()['sex'],
                'city'       => $oauth_user->getOriginal()['city'],
                'province'   => $oauth_user->getOriginal()['province'],
                'country'    => $oauth_user->getOriginal()['country'],
                'union_id'   => '', // todo
            ]));
        }

        $participant = Participant::whereActivityId($activity_id)->whereUserId($wechat_user->user_id)->first();
        // 当前授权用户为当前 team 的创建者时只做展示
        if ($team->user_id != $wechat_user->id) {
            if ($participant) {
                // 已支持过其他团队
                if ($participant->team_id != $team_id) {
                    return view('activity_team_supported')->with(['team' => $team]);
                }
            } else {
                Participant::create([
                    'activity_id' => $activity_id,
                    'team_id'     => $team_id,
                    'user_id'     => $wechat_user->user_id,
                ]);
                $team->count += 1;
                $team->save();
            }
        }

        $sort = Team::whereActivityId($activity_id)->where('count', '>', $team->count)->count() + 1;

        return view('activity_team')->with(['activity' => $activity, 'team' => $team, 'sort' => $sort]);
    }
}
