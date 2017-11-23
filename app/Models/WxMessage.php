<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WxMessage extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $casts = [
        'to' => 'object',
        'content' => 'object'
    ];

    // 文章
    const TYPE_ARTICLE = 1;
    // 图片
    const TYPE_IMAGE = 2;
    // 视频
    const TYPE_VIDEO = 3;
    // 音频
    const TYPE_VOICE = 4;
    // 图文
    const TYPE_NEWS = 5;
    // 文本
    const TYPE_TEXT = 6;

    // 未发送
    const STATUS_NEW = 1;
    // 发送中
    const STATUS_ING = 2;
    // 已发送
    const STATUS_SENDED = 3;
    // 取消发送
    const STATUS_CANCELD = 4;
}