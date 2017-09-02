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
        $now_time  = date('H:i', time());
        $reminders = Reminder::where('time', $now_time)->get();
        /** @var \EasyWeChat\Notice\Notice $notice */
        $notice = EasyWeChat::notice();

        $this->info('【' . $now_time . '】本次共' . $reminders->count() . '条消息需要发送');
        $reminders->each(function (Reminder $reminder) use ($notice) {
            $user           = $reminder->user;
            $union_id       = $user->wechatUser->union_id;
            $mp_wechat_user = WechatUser::where('user_id', '!=', $user->id)->whereUnionId($union_id)->first();
            $mp_user_openid = $mp_wechat_user->openid;

            $msg_data = [
                'first'    => '大大大大侠，今日背单词时间到了',
                'keyword1' => $user->wordbookState->wordbook->name, // 学习计划
                'keyword2' => '已打卡 ' . $user->sign_count . ' 天', // 学习时间
                'remark'   => '快开始背单词吧',
            ];

            $params = [
                'touser'      => $mp_user_openid,
                'template_id' => env('WECHAT_MP_TMP_ID'),
                'url'         => '',
                'data'        => $msg_data,
            ];

            if (app()->environment('product')) {
                $params['miniprogram'] = [
                    'appid'    => config('wechat.mini_program.app_id'),
                    'pagepath' => 'pages/index/index',
                ];
            }
            $msg_response = $notice->send($params);

            Log::info('$msg_response', ['msg_response' => $msg_response, 'msg_data' => $msg_data]);
        });
        $this->info('【' . $now_time . '】本次' . $reminders->count() . '条消息发送结束');
    }
}
