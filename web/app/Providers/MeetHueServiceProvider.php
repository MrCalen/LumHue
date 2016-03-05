<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MeetHueServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('meethue', 'App\Helpers\MeetHueModel\MeetHue' );
    }
}
