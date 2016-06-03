<?php


namespace app\Helpers\WebServices\Luis;

use App\Helpers\LumHueColorConverter;
use Auth;
use App\QueryBuilder\LightQueryBuilder;

class ColorIntent
{
    public function applyIntent($intent, $meethue_token)
    {
        $actions = $intent->actions;
        $parameters = [];
        foreach ($actions as $action) {
            foreach ($action->parameters as $parameter) {
                $parameters[] = $parameter;
            }
        }

        $parameters = LuisIntent::getColorParameters($parameters);
        $lightQueryBuidler = LightQueryBuilder::create($parameters['index'], $meethue_token);
        $lightQueryBuidler->setProperty('on', true);

        // Set color
        $colorstr = $parameters['color'];
        $light_color = LumHueColorConverter::RGBstrToRGB($colorstr);
        $hueColors = LumHueColorConverter::RGBtoChromatic($light_color[0], $light_color[1], $light_color[2]);
        $lightQueryBuidler
            ->setProperty('on', true)
            ->setProperty('xy', [
                $hueColors['x'],
                $hueColors['y'],
            ])
            ->apply();
    }
}
