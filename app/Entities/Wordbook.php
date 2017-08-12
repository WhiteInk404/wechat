<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wordbook extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'sort'];

    public function contents()
    {
        return $this->hasMany(WordbookContent::class);
    }
}
