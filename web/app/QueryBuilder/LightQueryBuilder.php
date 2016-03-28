<?php
declare(strict_types=1);

namespace App\QueryBuilder;

use App\Helpers\MeetHue;
use App\Helpers\MongoHue;
use App\Models\HueLight;

class LightQueryBuilder
{

    protected $light;

    private function __construct(HueLight $light)
    {
        $this->light = $light;
    }

    public static function create(HueLight $light) : LightQueryBuilder
    {
        return new LightQueryBuilder($light);
    }

    public function setProperty($propertyName, $value) : LightQueryBuilder
    {
        $this->light->setProperty($propertyName, $value);
        return $this;
    }

    public function setProperties(array $values) : LightQueryBuilder
    {
        foreach ($values as $key => $value) {
            $this->setProperty($key, $value);
        }
        return $this;
    }

    public function apply($token)
    {
        MeetHue::applyLightStatus($this->light, $token);
    }
}
