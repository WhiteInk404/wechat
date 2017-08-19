<?php

namespace App\Jobs;

use EasyWeChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Image;
use QrCode;

class MakeActivityQr implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $wechat_user;
    private $activity;
    private $team;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wechat_user, $activity, $team)
    {
        $this->wechat_user = $wechat_user;
        $this->activity    = $activity;
        $this->team        = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url     = route('activity_team', ['activity_id' => $this->activity->id, 'team_id' => $this->team->id]);
        $img_url = $this->activity->full_pic_url;
        $image   = Image::make($img_url);

        /** @var \EasyWeChat\Material\Temporary $temp */
        $temp = EasyWeChat::material_temporary();
        $path = storage_path(uniqid() . '.png');
        $image->insert(QrCode::format('png')->size(100)->margin(0)->generate($url), 'center')->save($path);
        $result   = $temp->uploadImage($path);
        $media_id = $result->get('media_id');

        $message = new EasyWeChat\Message\Image(['media_id' => $media_id]);

        /** @var \EasyWeChat\Staff\Staff $staff */
        $staff  = EasyWeChat::staff();
        $result = $staff->message($message)->to($this->wechat_user->openid)->send();
        \Log::info('make activity qr', ['user' => $this->wechat_user, 'activity' => $this
            ->activity, 'team'                 => $this->team, 'result' => $result, ]);
    }
}
