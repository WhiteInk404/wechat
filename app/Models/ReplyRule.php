<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReplyRule extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    // 启用
    const STATUS_YES = 1;
    // 停用
    const STATUS_NO = 0;
    // 关注回复
    const KEYWORD_SUBSCRIBE = 'SYSTEM_EVENT_SUBSCRIBE';
    // 默认回复
    const KEYWORD_NOMATCH = 'SYSTEM_EVENT_NOMATCH';
    // 内置关键字
    const TYPE_BUILDIN = 1;
    // 自定义关键字
    const TYPE_CUSTOM = 2;
    // 完全匹配
    const MATCH_TYPE_COMPLETE = 1;
    // 模糊匹配
    const MATCH_TYPE_FUZZY = 2;

    /**
     * 回复文本
     */
    const REPLY_TYPE_TEXT = 6;

    /**
     * 回复图片
     */
    const REPLY_TYPE_IMAGE = 2;
    
    // 视频
    const REPLY_TYPE_VIDEO = 3;
    // 音频
    const REPLY_TYPE_VOICE = 4;
    // 图文
    const REPLY_TYPE_NEWS = 5;
    // 文章
    const REPLY_TYPE_ARTICLE = 1;

    protected $fillable = ['cid', 'keyword', 'type'];
    
    public function media()
    {
        return $this->belongsTo('App\Models\Media', 'mid');
    }
}