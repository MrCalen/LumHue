<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Facade;

class MeetHue extends Facade
{
    protected static function getFacadeAccessor() { return 'meethue'; }
}
