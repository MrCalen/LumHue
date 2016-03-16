<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HueMailProvider extends ServiceProvider
{
    public function boot()
    {}

    public function register()
    {
        $this->app->bind('huemail', 'App\Helpers\HueMail\HueMail');
    }
}
