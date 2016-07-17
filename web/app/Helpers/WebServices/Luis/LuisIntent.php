<?php

namespace App\Helpers\WebServices\Luis;

class LuisIntent
{
    public static $intents = [
        'lightnumber' => [
            'premiere'    => 1,
            'première'    => 1,
            '1ere'        => 1,
            '1ère'        => 1,

            'deuxième'    => 2,
            "deux"        => 2,
            'deuxieme'    => 2,
            '2e'          => 2,
            '2ème'        => 2,
            '2'           => 2,
            '2eme'        => 2,
            'seconde'     => 2,
            'de'          => 2,

            'troisième'   => 3,
            'troisieme'   => 3,
            '3e'          => 3,

        ],
        'color'       => [
            'rouge'       => 'rgb(255, 0, 0)',
            'bleu'        => 'rgb(0, 0, 186)',
            'jaune'       => 'rgb(255, 255, 98)',
            'orange'      => 'rgb(255, 145, 98)',
            'blanc'       => 'rgb(255, 255, 255)',
            'vert'        => 'rgb(69, 139, 0)',
            'verre'       => 'rgb(69, 139, 0)',
        ],
    ];

    public static function getLightParameters($parameters)
    {
        $foundParameters = [];
        foreach ($parameters as $intent) {
            if ($intent->type === 'lightnumber') {
                $foundParameters['index'] = self::getIntent($intent);
            }
        }
        return $foundParameters;
    }

    public static function getColorParameters($parameters)
    {
        $foundParameters = [];
        foreach ($parameters as $parameter) {
            foreach ($parameter->value as $intent) {
                if ($intent->type === 'lightnumber') {
                    $foundParameters['index'] = self::getIntent($intent);
                } elseif ($intent->type === 'color') {
                    $foundParameters['color'] = self::getIntent($intent);
                }
            }
        }

        return $foundParameters;

    }

    public static function getIntent($value)
    {
        if (!isset(LuisIntent::$intents[$value->type][$value->entity])) {
            return null;
        }
        return LuisIntent::$intents[$value->type][$value->entity];
    }

    public static function applyIntent($intent, $meethue_token)
    {
        if ($intent->intent === 'light') {
            $luisintent = new LightIntent();
        } elseif ($intent->intent === 'color') {
            $luisintent = new ColorIntent();
        } else {
            return null;
        }

        try {
            $luisintent->applyIntent($intent, $meethue_token);
        } catch (\Throwable $e) {
            return null;
        }
        return $intent->intent;
    }
}
