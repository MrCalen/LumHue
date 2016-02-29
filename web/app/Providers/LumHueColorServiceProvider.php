<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\LumHueColorConverter;

class LumHueColorServiceProvider extends ServiceProvider
{
    public function register()
    {
      $this->app->singleton(LumHueColorConverter::class, function ($app) {
        return new LumHueColorConverter();
      });
    }
}
