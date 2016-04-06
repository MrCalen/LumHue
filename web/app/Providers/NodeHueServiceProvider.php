<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NodeHueServiceProvider extends ServiceProvider
{
    /**
    * Register the application services.
    *
    * @return void
    */
    public function register()
    {
        $this->app->bind('nodehue', 'App\QueryBuilder\NodeHue\NodeHue');
    }
}
