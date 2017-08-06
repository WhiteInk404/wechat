<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WechatUser extends Model
{
    protected $hidden   = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'openid', 'nickname', 'gender', 'city', 'province', 'country', 'avatar_url', 'union_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
