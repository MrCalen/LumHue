<?php


namespace app\Helpers\HueMail;

use Illuminate\Support\Facades\Facade;


class HueMailFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'huemail'; }

}