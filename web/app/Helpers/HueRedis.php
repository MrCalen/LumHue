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
            $user = \JWTAuth::toUser();

            list($bri, $x, $y) = array_values($light->getColor());

            $msg = [
                'light_id' => $light->getId(),
                'light' => $light->toArray(),
                'color' => LumHueColorConverter::chromaticToRGB($x, $y, $bri),
                'token' => $access_token,
//                'user' => $user
            ];
            $tmp->getRedis()->publish('lights', json_encode($msg));
        } catch (\Throwable $e) {
            //
            dd($e->getMessage());
        }
    }
}
