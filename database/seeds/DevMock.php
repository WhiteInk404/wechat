<?php

use App\Entities\Activity;
use App\Entities\Content;
use App\Entities\Cover;
use App\Entities\Role;
use App\Entities\Story;
use app\Entities\StoryComment;
use App\Entities\Userinfo;
use App\Entities\Video;
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
            [
                'name'         => 'vip',
                'display_name' => '会员',
                'description'  => '会员',
            ],
            [
                'name'         => 'member',
                'display_name' => '普通成员',
                'description'  => '普通成员',
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

        // vip
        $titles = ['趣单身 Vol.1 | 张小北 - 对生活明确且清晰的信仰「人物视频」', '趣单身 Vol.2 | 马燕妮 - 我有一颗好奇心「人物视频」', '趣单身 Vol.3 | 家菊 - 简单 纯粹 自在 自由「人物视频」', '趣单身 Vol.4 | 余小楠 - 冻龄少女心「人物视频」', '趣单身 Vol.5 | 万芫 - 给无趣的世界添点儿料「人物视频」', '趣单身 Vol.6 | 刘楠 - 为自在生活解绑「人物视频」'];
        $names  = ['张小北', '马燕妮', '家菊', '余小楠', '万芫', '刘楠'];
        for ($i = 0; $i < 6; $i++) {
            $vip = User::create([
                'name'     => 'vip_' . $i,
                'email'    => 'vip_' . $i . '@example.com',
                'password' => bcrypt('password'),
            ]);
            $vip->attachRole(Role::find(2));

            // vip userinfo
            $vip->userinfo()->save(new Userinfo([
                'gender'    => Userinfo::GENDER_MALE,
                'real_name' => $names[$i],
                'career'    => '程序员',
                'phone'     => '18666666666',
                'location'  => '北京',
                'intro'     => '阳光' . $names[$i],
                'birthday'  => Carbon::now()->subYear(18),
            ]));

            // vip wechat_app_user
            $vip->wechatAppUser()->save(new WechatUser([
                'openid'     => 'mario_openid_for_test',
                'nickname'   => $names[$i],
                'gender'     => Userinfo::GENDER_MALE,
                'city'       => '北京',
                'province'   => '北京',
                'country'    => '北京',
                'avatar_url' => 'http://oojewa8e5.bkt.clouddn.com/demo/cat_108x108.png',
                'union_id'   => 'mario_union_id_for_test',
            ]));

            // vip story
            $vip->story()->save(new Story(['title' => $titles[$i]]));

            $body = <<<'BODY'
<section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
    <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; font-size: 22px;">
            <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; text-align: center; word-wrap: break-word !important;">
                <strong style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">&nbsp;原 创 人 物 视 频 」<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/></strong>
            </p>
        </section>
    </section>
</section>
<p>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    衡量单身生活是否有趣的标准是什么？我就是标准，我定义有趣。我拿起剪刀端起相机，捕捉到你不曾看过的光影画面。黑夜给了你黑色的眼睛，我却用它寻找光明。我让这世界成了我要的样子，给这无趣的世界添点儿料。
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center; font-size: 22px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <strong style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">「 我 是 万 芫 ，IAMS 」</strong>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkia2sd8hNbCI8nC7ZAt0ianM0ibdO4gzu2vTRachqO93hYjCOGGibzfTeSQ/0" data-ratio="0.1625" data-w="640" data-type="png" src="https://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkia2sd8hNbCI8nC7ZAt0ianM0ibdO4gzu2vTRachqO93hYjCOGGibzfTeSQ/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; border-style: none none none solid; border-width: 1px 1px 1px 0px; border-radius: 0px; border-color: rgb(72, 69, 69); width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVk6GlM7VVFiazfrlNYYh5uDJClp8myPtoNibqZ9uPOan62D13wgEYa2ia8A/0" data-ratio="0.1625" data-w="640" data-type="png" src="https://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVk6GlM7VVFiazfrlNYYh5uDJClp8myPtoNibqZ9uPOan62D13wgEYa2ia8A/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkSnvV5icHWGRCmABaVj0oq6JrXhicB5ksicqaHh9nB9de5EugibBCCesS4A/0" data-ratio="0.1625" data-w="640" data-type="png" src="https://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkSnvV5icHWGRCmABaVj0oq6JrXhicB5ksicqaHh9nB9de5EugibBCCesS4A/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkMIpySEFImtCGu6hDG9VwibbncWa0CkZ33cAianffFISB16xQerDlXGFQ/0" data-ratio="1" data-w="640" width="20%" data-type="jpeg" _width="20%" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkMIpySEFImtCGu6hDG9VwibbncWa0CkZ33cAianffFISB16xQerDlXGFQ/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: 79px; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; text-align: center; word-wrap: break-word !important;">
                    <strong style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"><span style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; font-size: 22px;">「 &nbsp;Q &nbsp;&amp; &nbsp;A &nbsp;」</span></strong><br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <span style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; letter-spacing: 0px;"></span>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    Q：单身带给你最棒的体验是什么？<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    A：想干嘛就干嘛，说走就走。
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    &nbsp;<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    Q：习惯了单身，你还会对爱情有所期待吗？
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    A：当然，每次逛宜家的时候我都特别憧憬可以两个人。
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    Q：你对「趣单身」有着怎样的定义？
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    A：王小波说过“一辈子很长，要找个有趣的人在一起”。单身并不孤独，我想年底去新西兰跳伞。
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px 25px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    毕业于专业美术院校，现在身为一名美编，万芫对生活中细微之处的美有一种敏锐的感知和洞察力。而摄影，就是她感知生活和捕捉美好的一种方式。爱摄影的人总是对某一类事物有特别的执着和喜爱。对万芫来说，这个问题的答案就是「天空」。
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    她笑称自己是不折不扣的天空爱好者，专业拍天20年，只因小时候看过的一部电影《心动》。电影里，金城武用相机拍下天空，然后认真的写下：这是我想你时的天空。自此，万芫便一发不可收拾的迷上了天空。<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkljm23beL3MsYicDAX2e2poIYj2U3MaXm1MIiahhkraq2xMCtSfHBK3jw/0" data-ratio="1.0515625" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkljm23beL3MsYicDAX2e2poIYj2U3MaXm1MIiahhkraq2xMCtSfHBK3jw/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkvYGQhm2XBiafNRqtV0ZmApVIjK6ic9BKBibJuEY2OAg0lcwf6LZqZTVnQ/0" data-ratio="1" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkvYGQhm2XBiafNRqtV0ZmApVIjK6ic9BKBibJuEY2OAg0lcwf6LZqZTVnQ/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkAkbJVZx6F0qZ6icOr7PEWrEAmOh1dibbHjJjte1VZSBqkAlZ11WG42lQ/0" data-ratio="1" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkAkbJVZx6F0qZ6icOr7PEWrEAmOh1dibbHjJjte1VZSBqkAlZ11WG42lQ/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    拍过富士山与晚霞映衬的绮丽天空，留下鸟儿掠过天空的身影，抓拍到了浅蓝天空里绵厚呈热气球状的云，也复刻下了每一片不同天空下的别样回忆。<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    如万芫所说，“每次翻看照片，就可以回到当时那个时间点。”那些留存在胶片里的光影瞬间累积了生活细节，也延伸了彼时温暖的回忆。最平凡却也最可贵。<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkIsCic7bI7qiblQ6mGIhR9EslJwBhYg4Xt2MKwmGrcGffTMJ7b2XT4TUA/0" data-ratio="1" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkIsCic7bI7qiblQ6mGIhR9EslJwBhYg4Xt2MKwmGrcGffTMJ7b2XT4TUA/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    谈起单身的好处，万芫坦言可以说走就走，不用考虑太多。每次旅行都是一拍大腿就定下来的，一点也不犹豫。“我很喜欢一个人旅行，很享受那种漫不经心遇见的惊喜和随性而定的行程。”<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVknIJV3WDR6SLYy2yWpiaYzqnViaLkj0gicLgTZy4JLCSFqwIIpGZE46kJQ/0" data-ratio="0.6671875" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVknIJV3WDR6SLYy2yWpiaYzqnViaLkj0gicLgTZy4JLCSFqwIIpGZE46kJQ/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    20岁的时候独自旅行去香港，一个人去迪士尼、登太平山顶、随处逛吃找美食。“晚上看完迪士尼烟火，从地铁站打车回去的时候，司机师傅开了一条特别黑的道儿，我当时脑补出TVB里很多刑侦画面，而且因为没有零钱，还特怕司机师傅不找零。结果他不但没嫌找零麻烦，还提醒我不要忘带东西，当时感觉特别温暖。”<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    说到这里，万芫顿了一下，“硬要说一个人旅行的缺点嘛，就是好吃的东西没办法点太多，肚子撑不下”。说罢，自己就先笑了。
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    日本旅行的时候，在前往地铁站的路上偶然发现了一间神社，抽了新年第一签；随处逛逛，转角遇到一家有意思的中古二手店；就连坐错线，都能坐到可爱的车厢。
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkW2OBVeXzKMeiaxibSJglTpkUibibwiafFEJJLHr6zDplfcUV42mgojUY0Pw/0" data-ratio="1" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkW2OBVeXzKMeiaxibSJglTpkUibibwiafFEJJLHr6zDplfcUV42mgojUY0Pw/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkOlcic86Xlllg9315eBx3IxJt0M1BHCMFPZgUUWgM1JicxUQ3w3MibEBZg/0" data-ratio="0.75" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkOlcic86Xlllg9315eBx3IxJt0M1BHCMFPZgUUWgM1JicxUQ3w3MibEBZg/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVk6XPL9EicicNjSTNt2lUL4lGmRXIUUmXtF6hz1qJ5z1jKT2vvIYiaJeATA/0" data-ratio="1" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVk6XPL9EicicNjSTNt2lUL4lGmRXIUUmXtF6hz1qJ5z1jKT2vvIYiaJeATA/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;"></section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    独自旅行的随性和自由往往能带来结伴出游所感受不到的体验和乐趣，没有一板一眼的旅行List，看到喜欢的画面就举起相机记录下来，每一个意外的发现都足够让万芫欣喜。<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    单身的这些年，万芫没有停下探索世界的脚步，不管是用脚步丈量，还是用镜头捕捉，又或者是用味蕾感知。不论是以哪种方式，万芫始终选择用有趣的方式「行走在路上」。用她自己的话来说：心比地球大。
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkjKwKVbbN4UIsLynuV3JTHyRRt3WCic76q3WxkkKOZ8v3RGJvwhZgtEw/0" data-ratio="1" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkjKwKVbbN4UIsLynuV3JTHyRRt3WCic76q3WxkkKOZ8v3RGJvwhZgtEw/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkK5BQdB19z9ECEUXHmibyiaIZdyaxD1cR3hJwLXYN3W2SrhqFPGxIywaA/0" data-ratio="0.5625" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkK5BQdB19z9ECEUXHmibyiaIZdyaxD1cR3hJwLXYN3W2SrhqFPGxIywaA/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkcQSLNlc9VX26C2ApuXCUgibtyC4vl6BQib1wYIaCGTlZ9ZGwm9cLO4ww/0" data-ratio="1.5078125" data-w="640" data-type="jpeg" src="https://mmbiz.qpic.cn/mmbiz_jpg/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkcQSLNlc9VX26C2ApuXCUgibtyC4vl6BQib1wYIaCGTlZ9ZGwm9cLO4ww/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 15px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; transform: translate3d(0px, 0px, 0px); text-align: center;">
            <section class="" style="margin: 0px; padding: 0px 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 5px;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    每一个有趣的个体都对周围的生活和未曾踏足的世界有着强烈的好奇心和体验欲。他们自身的有趣和丰富是这周而复始的机械社会中难能可贵的存在。<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    做个有趣的人，比生活本身有趣更重要。<br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <br style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;"/>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: right; font-size: 12px; color: rgb(160, 160, 160); line-height: 1.8;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    音画构成 | vedio
                </p>
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <span style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; letter-spacing: 0px;">&nbsp;May | interview &amp; text</span>
                </p>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" " data-src="http://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkTfTM0ibVsBf99YZTRoPnmNb0JwHEaK2gNdiaUsbjvBEibTibPZEK3gPjBQ/0" data-ratio="0.3890625" data-w="640" data-type="png" src="https://mmbiz.qpic.cn/mmbiz_png/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkTfTM0ibVsBf99YZTRoPnmNb0JwHEaK2gNdiaUsbjvBEibTibPZEK3gPjBQ/640" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 3px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; border-width: 3px; border-style: solid; border-color: black;">
                <section class="" style="margin: 0px; padding: 10px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; border-color: rgb(128, 128, 128); border-width: 1px; border-style: solid;">
                    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
                            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: justify; font-size: 14px; color: rgb(112, 109, 109); line-height: 1.7; letter-spacing: 3px;">
                                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                                    <span style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; letter-spacing: 0px;">我们的生活方式无需循规蹈矩，单身生活亦然。他们遵循内心追求自由；她们在婚姻之外找到自己全新的故事。</span><span style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; letter-spacing: 0px;">每一期，《趣单身》都会邀请一位有趣的单身人，分享他的生活观和小日子。定义生活有趣与否，你才是起决定作用的主角。</span>
                                </p>
                            </section>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 10px 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
            <img class=" __bg_gif " data-src="http://mmbiz.qpic.cn/mmbiz_gif/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkwyLl0DMmGQbTFiazickRdPayfjwh1YKaxLltTKpUolNcE5NiczTCMkNlQ/0" data-ratio="0.5914285714285714" data-w="700" data-type="gif" src="https://mmbiz.qpic.cn/mmbiz_gif/Df4Qrejxfo7r8RUyVUPPbUlUP36taSVkwyLl0DMmGQbTFiazickRdPayfjwh1YKaxLltTKpUolNcE5NiczTCMkNlQ/0" data-order="0" data-fail="0" style="margin: 0px; padding: 0px; height: auto !important; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; vertical-align: middle; width: auto !important; visibility: visible !important;"/>
        </section>
    </section>
    <section class="" powered-by="xiumi.us" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; color: rgb(62, 62, 62); font-family: -apple-system-font, &quot;Helvetica Neue&quot;, &quot;PingFang SC&quot;, &quot;Hiragino Sans GB&quot;, &quot;Microsoft YaHei&quot;, sans-serif; font-size: 16px; white-space: normal; word-wrap: break-word !important;">
        <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important;">
            <section class="" style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; word-wrap: break-word !important; text-align: center;">
                <p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; box-sizing: border-box; clear: both; min-height: 1em; word-wrap: break-word !important;">
                    <span style="margin: 0px; padding: 0px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 215, 213);">- 自 在 生 活 ，自 在 单 身 -</span>
                </p>
            </section>
        </section>
    </section>
</p>
<p>
    <br/>
</p>
BODY;

            $summary = '谈起单身的好处，万芫坦言可以说走就走，不用考虑太多。每次旅行都是一拍大腿就定下来的，一点也不犹豫。“我很喜欢一个人旅行，很享受那种漫不经心遇见的惊喜和随性而定的行程。”';

            $content = new Content(['body' => $body, 'summary' => $summary]);
            $vip->story->content()->save($content);

            // 封面
            $vip->story->covers()->saveMany([
                new Cover(['path' => 'demo/story_' . ($i + 1) . '.png', 'type' => 1]),
                new Cover(['path' => 'demo/story_' . ($i < 5 ? $i + 2 : 1) . '.png', 'type' => 1]),
            ]);

            // 视频
            $vip->story->video()->save(new Video(['path' => 'videos/2a0d7d15dd124e0e34df53f0527cd7e4.mp4', 'info' => '{ "format": { "duration": "65.179000", "size": "1619180"} }']));
        }

        // 评论
        $story = Story::first();
        $story->comments()->saveMany([
            new StoryComment(['user_id' => 2, 'content' => '喜欢！']),
            new StoryComment(['user_id' => 3, 'content' => '选我选我。']),
            new StoryComment(['user_id' => 4, 'content' => '额，哈哈，约一个！']),
        ]);

        // 喜欢
        User::find(2)->upVote($story);
        $story->increment('like_count');
        $story_2 = Story::find(2);

        User::find(3)->upVote($story_2);
        $story_2->increment('like_count');

        $story_3 = Story::find(3);
        User::find(4)->upVote($story_3);
        $story_3->increment('like_count');

        // 普通成员
        $user = User::create([
            'name'     => 'user',
            'email'    => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->attachRole(Role::find(3));
        // 活动
        $this->activities();

        // 活动订单
        $this->activityOrders();
    }

    public function activityOrders()
    {
        $out_trade_no   = 'ac' . uniqid();
        $activity_order = \App\Entities\ActivityOrder::create([
            'user_id'       => 2,
            'activity_id'   => 1,
            'contact_phone' => '18600755313',
            'visibility'    => 1,
            'out_trade_no'  => $out_trade_no,
            'quantity'      => 1,
            'total_fee'     => 0,
        ]);
        $activity_order->order()->save(new \App\Entities\Order([
            'appid'        => 'appid_1234',
            'mch_id'       => 'mch_id_123',
            'openid'       => User::find(2)->wechatAppUser->openid,
            'out_trade_no' => $out_trade_no,
            'body'         => '测试活动报名',
            'total_fee'    => 0,
        ]));
    }

    public function activities()
    {
        $free_activity = Activity::create([
            'user_id'       => 1,
            'name'          => '派对|烛光晚餐交友派对文艺开趴，你要不要来参加！',
            'date'          => Carbon::today()->addWeek(),
            'address'       => '北京 朝阳区',
            'cost'          => 0,
            'contact_phone' => '18600755313',
            'total_num'     => 50,
            'qr_code'       => 'qr_codes/wechat.png',
        ]);
        $free_activity->covers()->saveMany([
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
        ]);
        $body    = <<<'BODY'
趣闻 1947 年，时装设计师 Elsa Schiaparelli 将“艳粉色”引入西方时尚圈。 桃色可以营造亲密氛围，减少攻击性和敌意。 由于听说粉色有一种镇定效果，有些球队会把客队的休息室漆成粉色。 对于粉色的研究发现，男性举重运动员在粉色房间内似乎感到力不从心，而女性举重运动员面对这种颜色反而会有变强的倾向。 糕点从粉色盒子里取出或盛在粉色盘子里时，尝起来会更美味（这种情况仅适用于甜点），因为粉色令我们渴望糖份。
BODY;
        $summary = '1947 年，时装设计师 Elsa Schiaparelli 将“艳粉色”引入西方时尚圈。 桃色可以营造亲密氛围，减少攻击性和敌意。 由于听说粉色有一种镇定效果，有些球队会把客队的休息室漆成粉色。';
        $content = new Content(['body' => $body, 'summary' => $summary]);
        $free_activity->content()->save($content);

        $activity = Activity::create([
            'user_id'       => 2,
            'name'          => '派对|烛光晚餐交友派对文艺开趴，你要不要来参加！',
            'date'          => Carbon::today()->addWeek(),
            'address'       => '北京 朝阳区',
            'cost'          => 0.01,
            'contact_phone' => '18600755313',
            'total_num'     => 50,
            'qr_code'       => 'qr_codes/wechat.png',
        ]);
        $activity->covers()->saveMany([
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
        ]);
        $content = new Content(['body' => $body, 'summary' => $summary]);
        $activity->content()->save($content);

        $expired_activity = Activity::create([
            'user_id'       => 3,
            'name'          => '派对|烛光晚餐交友派对文艺开趴，你要不要来参加！',
            'date'          => Carbon::today()->subDay(),
            'address'       => '北京 朝阳区',
            'cost'          => 0,
            'total_num'     => 50,
            'contact_phone' => '18600755313',
            'qr_code'       => 'qr_codes/wechat.png',
        ]);
        $expired_activity->covers()->saveMany([
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
            new Cover(['path' => 'demo/activity_01.png', 'type' => 1]),
        ]);
        $content = new Content(['body' => $body, 'summary' => $summary]);
        $expired_activity->content()->save($content);
    }
}
