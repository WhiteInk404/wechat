<?php

namespace App\Entities;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name', 'display_name', 'description'];

    const ROLE_ADMINISTRATOR = 1;
    const ROLE_VIP           = 2;
    const ROLE_MEMBER        = 3;
}
