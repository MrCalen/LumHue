<?php

declare(strict_types = 1);

namespace App\Helpers;

use App\Models\HueLight;
use Redis;

class HueRedis
{

    private $redis;

    private function __construct()
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1');
    }

    public function getRedis() : Redis
    {
        return $this->redis;
    }

    public static function publishLightState(HueLight $light, $access_token)
    {
        $tmp = new HueRedis();
        list($bri, $x, $y) = extract($light->getColor());
        $msg = [
            'light_id' => $light->getId(),
            'color' => LumHueColorConverter::chromaticToRGB($x, $y, $bri),
            'token' => $access_token,
        ];
        $tmp->getRedis()->publish('ws', json_encode($msg));
    }
}
