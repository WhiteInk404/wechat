<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WxQrcode extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 其他类型二维码
     */
    const TYPE_OTHER = 1;

    public static function getTypeArr()
    {
        $types = [
            '0' => '其他类型',
        ];
        return $types;
    }
}