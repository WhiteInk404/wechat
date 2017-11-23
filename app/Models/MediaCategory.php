<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaCategory extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'media_categories';


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
}
