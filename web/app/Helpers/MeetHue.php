<?php

namespace App\Helpers\MeetHue;

use Illuminate\Support\Facades\Facade;

class MeetHue extends Facade
{
    protected static function getFacadeAccessor() { return 'meethue'; }
}
