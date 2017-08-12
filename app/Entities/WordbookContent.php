<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class WordbookContent extends Model
{
    protected $fillable = ['wordbook_id', 'facade', 'back'];

    public function wordbook()
    {
        return $this->belongsTo(Wordbook::class);
    }
}
