<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WxMenu extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 默认菜单
     */
    const TYPE_DEFAULT = 1;

    /**
     * 个性化菜单
     */
    const TYPE_CONDITIONAL = 2;

    protected $casts = [
        'button' => 'object',
        'matchrule' => 'object'
    ];

    protected $table = 'wx_menus';
}