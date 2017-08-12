<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WordbookRecord extends Model
{
    protected $fillable = ['user_id', 'wordbook_id', 'wordbook_content_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
