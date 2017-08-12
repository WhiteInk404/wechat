<?php

namespace App\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WordRecord extends Model
{
    protected $fillable = ['user_id', 'wordbook_id', 'wordbook_content_id', 'status'];

    const STATUS_NOT_REMEMBER = 0; // 不记得
    const STATUS_REMEMBER     = 1; // 记得
    const STATUS_BLURRY       = 2; // 模糊

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
