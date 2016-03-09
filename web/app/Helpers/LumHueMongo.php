<?php

namespace App\Helpers\LumHueMongo;

use Illuminate\Support\Facades\Facade;

class LumHueMongo extends Facade
{
    protected static function getFacadeAccessor() { return 'mongo'; }
}
