<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WxPaymentCharge extends Model
{
    use SoftDeletes;

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $hidden = ['cid', 'pid', 'refund_status', 'refund_fee', 'created_at', 'updated_at', 'deleted_at'];

    const PAY_STATUS_NEW = 'NEW';
    const PAY_STATUS_SUCCESS = 'SUCCESS';
    const PAY_STATUS_FAIL = 'FAIL';

    const REFUND_STATUS_NO = 1;
    const REFUND_STATUS_YES = 2;

    public function getPayStatusAttribute($value)
    {
        $ret = '';
        switch ($value) {
            case 1:
                $ret = 'NEW';
                break;
            case 2:
                $ret = 'SUCCESS';
                break;
            case 3:
                $ret = 'FAIL';
                break;
            default:
                break;
        }
        return $ret;
    }

    public function setPayStatusAttribute($value)
    {
        $newValue = 0;
        switch ($value) {
            case 'NEW':
                $newValue = 1;
                break;
            case 'SUCCESS':
                $newValue = 2;
                break;
            case 'FAIL':
                $newValue = 3;
                break;
            default:
                break;
        }
        $this->attributes['pay_status'] = $newValue;
    }
}