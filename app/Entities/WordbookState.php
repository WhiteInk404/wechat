<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WordbookState extends Model
{
    protected $fillable = ['user_id', 'wordbook_id', 'word_total', 'remember_total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wordbook()
    {
        return $this->belongsTo(Wordbook::class);
    }

    public function wordbookContent()
    {
        return $this->belongsTo(WordbookContent::class);
    }
}
