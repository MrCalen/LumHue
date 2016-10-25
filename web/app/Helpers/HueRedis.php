<?php

declare(strict_types = 1);

namespace App\Helpers;

use App\Models\HueLight;
use Redis;
use JWAuth;

class HueRedis
{
    private $redis;

    private function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1');
    }

    public function getRedis() : Redis
    {
        return $this->redis;
    }

    public static function publishLightState(HueLight $light, $access_token)
    {
        try {
            $tmp = new HueRedis();
            \JWTAuth::setToken($access_token);

            list($bri, $x, $y) = array_values($light->getColor());
            $rgb = LumHueColorConverter::chromaticToRGB($x, $y, $bri);
            list($r, $g, $b) = array_values($rgb);
            $msg = [
                'light_id' => $light->getId(),
                'light' => $light->toArray(),
                'color' => $rgb,
                'rgbhex' => LumHueColorConverter::RGBToHex($r, $g, $b),
                'token' => $access_token,
            ];
            $tmp->getRedis()->publish('lights', json_encode($msg));
        } catch (\Throwable $e) {
            //
            dd($e->getMessage());
        }
    }
}
