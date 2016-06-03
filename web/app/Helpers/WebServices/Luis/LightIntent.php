<?php

namespace App\Helpers\WebServices\Luis;

use App\QueryBuilder\LightQueryBuilder;
use Auth;

class LightIntent
{

    public function applyIntent($intent, $meethue_token)
    {
        $parameters = $intent->actions[0]->parameters[0];
        $parameters = LuisIntent::getLightParameters($parameters->value);
        $lightQueryBuidler = LightQueryBuilder::create($parameters['index'], $meethue_token);
        $lightQueryBuidler->setProperty('on', true);
        $lightQueryBuidler->apply();
    }
}
