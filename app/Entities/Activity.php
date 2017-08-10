<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'begin_time', 'end_time', 'pic_url', 'labels'];
    protected $appends  = ['full_pic_url', 'left_label', 'right_label'];

    public function getFullPicUrlAttribute()
    {
        return env('QINIU_DOMAIN') . $this->attributes['pic_url'];
    }

    public function getLeftLabelAttribute()
    {
        $labels = explode(',', $this->attributes['labels']);

        return $labels[0];
    }

    public function getRightLabelAttribute()
    {
        $labels = explode(',', $this->attributes['labels']);

        return $labels[1];
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