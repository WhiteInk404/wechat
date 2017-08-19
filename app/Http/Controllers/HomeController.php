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
    public function up($activity_id, $team_id)
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

        $wechat_user       = WechatUser::whereOpenid($oauth_user->getId())->first();
        $other_participant = Participant::whereActivityId($activity_id)->whereUserId($wechat_user->user_id)->where('team_id', '!=', $team_id)->first();
        // 当前用户支持过当前活动的其他团队，不是当前团队的创建者
        if ($other_participant && $team->user_id != $wechat_user->user_id) {
            return view('activity_team_supported')->with(['team' => $other_participant->team]);
        }

        $exists = Participant::whereActivityId($activity_id)->whereTeamId($team_id)->whereUserId($wechat_user->user_id)->exists();
        // 当前用户已支持过当前活动
        if (!$exists) {
            Participant::create([
                'activity_id' => $activity_id,
                'team_id'     => $team_id,
                'user_id'     => $wechat_user->user_id,
            ]);
            $team->count += 1;
            $team->save();
        }

        return redirect()->back()->with(['success' => true]);
    }

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

            $wechat_user = new WechatUser([
                'openid'     => $oauth_user->getId(),
                'nickname'   => $oauth_user->getNickname(),
                'avatar_url' => $oauth_user->getAvatar(),
                'gender'     => $oauth_user->getOriginal()['sex'],
                'city'       => $oauth_user->getOriginal()['city'],
                'province'   => $oauth_user->getOriginal()['province'],
                'country'    => $oauth_user->getOriginal()['country'],
                'union_id'   => '', // todo
            ]);
            $user->wechatUser()->save($wechat_user);
        }

        $exists = Participant::whereActivityId($activity_id)->whereTeamId($team_id)->whereUserId($wechat_user->user_id)->exists();

        $sort = Team::whereActivityId($activity_id)->where('count', '>', $team->count)->count() + 1;

        return view('activity_team')->with(['activity' => $activity->append(['friendly_begin_time', 'friendly_end_time']), 'team' => $team, 'sort' => $sort, 'exists' => $exists]);
    }

    public function activityTeamMore($activity_id, $team_id)
    {
        /** @var \Overtrue\Socialite\User $oauth_user */
        $oauth_user = session('wechat.oauth_user');

        $wechat_user = WechatUser::whereOpenid($oauth_user->getId())->first();
        $activity    = Activity::find($activity_id);
        $team        = Team::find($team_id);
        if (!$activity || !$team) {
            return '活动信息有误';
        }

        if (strtotime($activity->end_time) < Carbon::now()->timestamp) {
            return view('activity_team_finished');
        }

        $participants = Participant::with('user')->whereActivityId($activity_id)->whereTeamId($team_id)->orderBy('id', 'desc')->get();
        $exists       = Participant::whereActivityId($activity_id)->whereTeamId($team_id)->whereUserId($wechat_user->user_id)->exists();

        return view('activity_team_more')->with(['participants' => $participants, 'activity' => $activity, 'team' => $team, 'exists' => $exists]);
    }
}
