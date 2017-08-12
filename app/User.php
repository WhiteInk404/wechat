<?php

namespace App;

use App\Entities\Activity;
use App\Entities\Participant;
use App\Entities\Reminder;
use App\Entities\SignRecord;
use App\Entities\Team;
use App\Entities\WechatUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    const PASSPORT_TYPE_NORMAL = 0;
    const PASSPORT_TYPE_WECHAT = 1;
    const PASSPORT_TYPE_WXA    = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'passport_type', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 签到次数
     *
     * @return mixed
     */
    public function getSignCountAttribute()
    {
        return $this->signRecords()->count();
    }

    public function wechatUser()
    {
        return $this->hasOne(WechatUser::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function reminder()
    {
        return $this->hasOne(Reminder::class);
    }

    public function signRecords()
    {
        return $this->hasMany(SignRecord::class);
    }
}
