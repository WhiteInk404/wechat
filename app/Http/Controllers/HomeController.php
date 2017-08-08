<?php

namespace App\Http\Controllers;

use App\Entities\Activity;
use App\Entities\Team;

class HomeController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = session('wechat.oauth_user');
    }

    public function activityTeam($activity_id, $team_id)
    {
        $activity = Activity::find($activity_id);
        $team     = Team::find($team_id);

        $sort = Team::whereActivityId($activity_id)->where('count', '>', $team->count)->count() + 1;

        if (!$activity || !$team) {
            return '活动信息有误';
        }

        return view('activity_team')->with(['activity' => $activity, 'team' => $team, 'sort' => $sort]);
    }
}
