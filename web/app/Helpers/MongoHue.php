<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Facade;

class MongoHue extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mongohue';
    }
}
