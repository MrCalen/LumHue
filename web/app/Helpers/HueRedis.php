<?php

declare(strict_types = 1);

namespace App\Helpers;

use Redis;

class HueRedis
{
    private static $instance;

    private $redis;

    private function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect("localhost");
    }

    public function getRedis() : Redis
    {
        return $this->redis;
    }

    public static function instance() : HueRedis
    {
        if (!self::$instance) {
            self::$instance = new HueRedis();
        }

        return self::$instance;
    }
}
