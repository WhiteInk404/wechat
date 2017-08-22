<?php

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WordbookContent extends Model
{
    protected $fillable = ['wordbook_id', 'facade', 'back'];

    public function wordbook()
    {
        return $this->belongsTo(Wordbook::class);
    }

    public function wordRecord()
    {
        return $this->hasMany(WordRecord::class);
    }

    public function getTodayWordsAttribute()
    {
        $user = \Auth::user();

        return WordRecord::whereUserId($user->id)
            ->whereStatus(WordRecord::STATUS_REMEMBER)
            ->where('created_at', '>=', Carbon::today())
            ->count();
    }
}
