<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = ['user_id', 'time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
