<?php

namespace App\Libraries\SensitiveWords\Facades;

use Illuminate\Support\Facades\Facade;

class SensitiveWordsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sensitive_words';
    }
}
