<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'begin_time', 'end_time', 'pic_url', 'labels'];

    public function getPicUrlAttribute()
    {
        return env('QINIU_DOMAIN') . $this->attributes['pic_url'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
