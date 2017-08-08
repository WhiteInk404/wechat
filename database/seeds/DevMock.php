<?php

use App\Entities\Activity;
use App\Entities\Participant;
use App\Entities\Role;
use App\Entities\Team;
use App\Entities\WechatUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DevMock extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 用户角色
        $roles = [
            [
                'name'         => 'administrator',
                'display_name' => '超级管理员',
                'description'  => '超级管理员',
            ],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        $admin = User::create([
            'name'     => 'admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        // 管理员权限
        $admin->attachRole(Role::find(1));

        // 活动
        $this->activities();
    }

    public function activities()
    {
        $activity = Activity::create([
            'name'        => '活动1名称',
            'description' => '活动1信息',
            'begin_time'  => Carbon::yesterday(),
            'end_time'    => Carbon::today()->addWeek(),
            'pic_url'     => 'demo/activity_01.png',
            'labels'      => '#,#',
        ]);

        $teams    = [];
        $team_num = random_int(20, 100);
        for ($i = 0; $i < $team_num; $i++) {
            $teams[] = new Team([
                'name'    => 'Team ' . $i,
                'user_id' => 0,
                'count'   => $i == 0 ? 100 : random_int(0, 100),
            ]);
        }
        $activity->teams()->saveMany($teams);

        // 创建100个用户支持了 team_id = 1 的
        for ($i = 0; $i < 100; $i++) {
            $user = User::create([
                'name'          => 'user' . $i,
                'passport_type' => 1,
                'email'         => 'user' . $i . '@example.com',
                'password'      => bcrypt('password'),
            ]);

            $user->wechatUser()->save(new WechatUser([
                'openid'     => 'openid' . $i,
                'nickname'   => 'nickname' . $i,
                'avatar_url' => 'http://placehold.it/80x80/&text=' . $i,
                'gender'     => random_int(0, 1),
                'city'       => 'Bj',
                'province'   => 'Bj',
                'country'    => 'China',
                'union_id'   => '',
            ]));

            Participant::create([
                'activity_id' => 1,
                'team_id'     => 1,
                'user_id'     => $user->id,
            ]);
        }
    }
}
