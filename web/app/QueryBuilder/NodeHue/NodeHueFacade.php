<?php

namespace App\QueryBuilder\NodeHue;

use Illuminate\Support\Facades\Facade;

class NodeHueFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'nodehue';
    }
}
