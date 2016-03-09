<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MongoDBServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mongohue', 'App\Helpers\MongoHueModel\MongoHue');
    }
}
