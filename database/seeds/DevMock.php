<?php

use App\Entities\Activity;
use App\Entities\Role;
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
    }
}
