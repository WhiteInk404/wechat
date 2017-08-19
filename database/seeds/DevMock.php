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

        // 小程序
        $this->miniProgram();
    }

    public function miniProgram()
    {
        $now      = Carbon::now();
        $wordbook = \App\Entities\Wordbook::create(['name' => 'wordbook 1']);

        $wordbook_contents = [
            ['wordbook_id' => $wordbook->id, 'facade' => 'abandon', 'back' => 'n. 放任；狂热 vt. 遗弃；放弃', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'abbreviation', 'back' => 'n. 缩写；缩写词', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'ability', 'back' => 'n. 能力，能耐；才能', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'able', 'back' => 'adj. 能；[经管] 有能力的；能干的 n. (Able)人名；(伊朗)阿布勒；(英)埃布尔', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'abnormal', 'back' => 'adj. 反常的，不规则的；变态的', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'aboard', 'back' => 'prep. 在…上 adv. 在飞机上；[船] 在船上；在火车上', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'abolish', 'back' => 'vt. 废除，废止；取消，革除', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'abortion', 'back' => 'n. 流产，堕胎，小产；流产的胎儿；（计划等）失败，夭折', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'above', 'back' => 'prep. 超过；在……上面；在……之上 n. 上文 adj. 上文的 adv. 在上面；在上文', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook->id, 'facade' => 'abroad', 'back' => 'n. 海外；异国 adj. 往国外的 adv. 在国外；到海外', 'created_at' => $now, 'updated_at' => $now],
        ];
        \App\Entities\WordbookContent::insert($wordbook_contents);
        \App\Entities\WordbookState::create([
            'user_id'        => 3,
            'wordbook_id'    => $wordbook->id,
            'word_total'     => count($wordbook_contents),
            'remember_total' => 0,
        ]);

        $wordbook2 = \App\Entities\Wordbook::create(['name' => 'wordbook 2']);

        $wordbook_contents = [
            ['wordbook_id' => $wordbook2->id, 'facade' => 'acceptance', 'back' => 'n. 接受', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook2->id, 'facade' => 'accepted', 'back' => 'v. 接受（accept的过去式及过去分词） adj. 公认的；录取的；可接受的；已承兑的', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook2->id, 'facade' => 'access', 'back' => 'n. 进入；使用权；通路 vt. 使用；存取；接近', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook2->id, 'facade' => 'accessory', 'back' => 'n. 配件；附件；[法] 从犯 adj. 副的；同谋的；附属的', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook2->id, 'facade' => 'accident', 'back' => 'n. 事故；意外；[法] 意外事件；机遇', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook2->id, 'facade' => 'accidental', 'back' => 'n. 次要方面；非主要的特性；临时记号 adj. 意外的；偶然的；附属的；临时记号的', 'created_at' => $now, 'updated_at' => $now],
            ['wordbook_id' => $wordbook2->id, 'facade' => 'accidentally', 'back' => 'adv. 意外地；偶然地', 'created_at' => $now, 'updated_at' => $now],
        ];
        \App\Entities\WordbookContent::insert($wordbook_contents);
    }

    public function activities()
    {
        $activity = Activity::create([
            'name'        => '活动1名称',
            'description' => '活动1信息',
            'begin_time'  => Carbon::yesterday(),
            'end_time'    => Carbon::today()->addWeek(),
            'pic_url'     => 'demo/640x1008.jpeg',
        ]);

        $teams    = [];
        $team_num = random_int(20, 40);
        for ($i = 0; $i < $team_num; $i++) {
            $teams[] = new Team([
                'name'    => 'Team ' . $i,
                'user_id' => 0,
                'count'   => $i == 0 ? 30 : random_int(0, 30),
            ]);
        }
        $activity->teams()->saveMany($teams);

        // 创建100个用户支持了 team_id = 1 的
        for ($i = 0; $i < 30; $i++) {
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
