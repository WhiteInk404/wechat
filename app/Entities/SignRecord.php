<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SignRecord extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
