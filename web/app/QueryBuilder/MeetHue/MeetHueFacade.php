<?php

namespace App\QueryBuilder\MeetHue;

use Illuminate\Support\Facades\Facade;

class MeetHueFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'meethue';
    }
}
