<?php

namespace App\Console\Commands;

use App\Entities\Reminder;
use App\Entities\WechatUser;
use EasyWeChat;
use Illuminate\Console\Command;
use Log;

class Remind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordbook:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '单词本提醒';

    private $template_id = 'MZMyq4Jn_wesptIpurscYeSwRlpZd5ecD_7edghjBpE';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now_time  = date('H:i');
        $reminders = Reminder::whereTime($now_time)->get();
        /** @var \EasyWeChat\Notice\Notice $notice */
        $notice = EasyWeChat::notice();

        $reminders->each(function (Reminder $reminder) use ($notice) {
            $user           = $reminder->user;
            $union_id       = $user->wechatUser->union_id;
            $mp_wechat_user = WechatUser::where('user_id', '!=', $user->id)->whereUnionId($union_id)->first();
            $mp_user_openid = $mp_wechat_user->openid;

            $msg_data     = [
                'first'    => '大大大大侠，今日背单词时间到了',
                'keyword1' => $user->wordbookState->wordbook->name, // 学习计划
                'keyword2' => '已打卡 ' . $user->sign_count . ' 天', // 学习时间
                'remark'   => '快开始背单词吧',
            ];
            $msg_response = $notice->send([
                'touser'      => $mp_user_openid,
                'template_id' => $this->template_id,
                'url'         => '',
                'miniprogram' => [
                    'appid'    => config('wechat.mini_program.app_id'),
                    'pagepath' => 'index',
                ],
                'data'        => $msg_data,
            ]);

            Log::info('$msg_response', ['msg_response' => $msg_response, 'msg_data' => $msg_data]);
        });
    }
}
