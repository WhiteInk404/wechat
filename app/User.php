<?php

namespace App;

use App\Entities\Activity;
use App\Entities\Team;
use App\Entities\WechatUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'passport_type', 'email', 'password',
    ];

    protected $appends = ['is_vip'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
}
