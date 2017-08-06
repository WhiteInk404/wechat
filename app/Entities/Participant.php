<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['activity_id', 'team_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
