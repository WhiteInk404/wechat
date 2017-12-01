<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use EasyWeChat;


class SendStaffMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $wechat_user;
//    private $flag;
//    private $message->Content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wechat_user)
    {
        $this->wechat_user = $wechat_user;
//        $this->$flag = $flag;
//        $this->$message->Content = $message->Content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//      if ($this->$flag == '7000') {

          /** 新建文本消息 */
          $message = new EasyWeChat\Message\Text(['content' => '如果有疑问，请添加客服微信：xuechun_1991咨询。']);

          /** @var \EasyWeChat\Staff\Staff $staff */
          $staff  = EasyWeChat::staff();
          $result = $staff->message($message)->to($this->wechat_user->openid)->send();
//      }
    }
}
