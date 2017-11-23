<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    public $timestamps = false;
    public $primaryKey = 'user_id';
    public function roles()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }
}
