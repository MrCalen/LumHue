<?php

namespace App\Helpers\WebServices\Luis;

use App\QueryBuilder\LightQueryBuilder;
use Auth;

class LightIntent
{

    public function applyIntent($intent)
    {
        $parameters = $intent->actions[0]->parameters[0];
        $parameters = LuisIntent::GetLightParameters($parameters->value);
        $lightQueryBuidler = LightQueryBuilder::create($parameters['index'], Auth::user()->meethue_token);
        $lightQueryBuidler->setProperty('on', true);
        $lightQueryBuidler->apply();
    }
}
