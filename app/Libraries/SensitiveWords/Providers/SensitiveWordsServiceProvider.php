<?php

namespace App\Libraries\SensitiveWords\Providers;

use App\Libraries\SensitiveWords\SensitiveWords;
use Illuminate\Support\ServiceProvider;

class SensitiveWordsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind('sensitive_words', function () {
            return new SensitiveWords();
        });
    }
}
