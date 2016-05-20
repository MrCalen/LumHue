<?php

namespace App\Helpers\WebServices\Luis;

class LuisIntent
{
    public static $intents = [
        'lightnumber' => [
            'premiere'    => 1,
            'première'    => 1,
            '1ere'        => 1,

            'deuxième'    => 2,
            'deuxieme'    => 2,
            '2e'          => 2,
            'seconde'     => 2,

            'troisième'   => 3,
            'troisieme'   => 3,
            '3e'          => 3,

        ],
        'color'          => [
            'rouge'        => 'rgb(255, 0, 0)',
            'bleu'        => 'rgb(0, 0, 186)',
            'jaune'        => 'rgb(255, 255, 98)',
            'orange'        => 'rgb(255, 145, 98)',
            'blanc'        => 'rgb(255, 255, 255)',
        ],
    ];

    public static function GetLightParameters($parameters)
    {
        $foundParameters = [];
        foreach ($parameters as $intent) {
            if ($intent->type === 'lightnumber')
                $foundParameters['index'] = self::GetIntent($intent);
        }
        return $foundParameters;
    }

    public static function GetColorParameters($parameters)
    {
        $foundParameters = [];
        foreach ($parameters as $parameter) {
            foreach ($parameter->value as $intent) {

                if ($intent->type === 'lightnumber')
                    $foundParameters['index'] = self::GetIntent($intent);
                else if ($intent->type === 'color')
                    $foundParameters['color'] = self::GetIntent($intent);
            }
        }

        return $foundParameters;

    }

    public static function GetIntent($value)
    {
        if (!isset(LuisIntent::$intents[$value->type][$value->entity])) {
            return null;
        } else
            return LuisIntent::$intents[$value->type][$value->entity];
    }

    public static function ApplyIntent($intent, $meethue_token)
    {
        if ($intent->intent === 'light')
            $luisintent = new LightIntent();
        elseif ($intent->intent === 'color')
            $luisintent = new ColorIntent();
        else
            return null;
        try {
            $luisintent->ApplyIntent($intent, $meethue_token);
        } catch (\Throwable $e) {
            return null;
        }
        return $intent->intent;
    }
}
