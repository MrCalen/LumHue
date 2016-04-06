<?php
declare(strict_types=1);

namespace App\QueryBuilder;

use App\Models\HueLight;
use NodeHue;
use MongoHue;
use MeetHue;

class LightQueryBuilder
{
    protected $light;
    protected $access_method = 'node';
    protected $meethue_token = null;

    private function __construct($light_id = null)
    {
        if ($light_id == null)
            return;
        $lights = json_decode(NodeHue::getBridgeStatus());
        $light = $lights->lights[$light_id];
        $this->light = new HueLight($light_id, $light);
    }

    public static function create($light_id) : LightQueryBuilder
    {
        return new LightQueryBuilder($light_id);
    }

    public function setLight(HueLight $light) : LightQueryBuilder
    {
      $this->light = $light;
    }

    public function viaMeetHue($meethue_token) : LightQueryBuilder
    {
      $this->access_method = 'meethue';
      $this->meethue_token = $meethue_token;
    }

    public function getBridgeState()
    {
      if ($this->access_method === 'node')
        return NodeHue::getBridgeStatus();
      return MeetHue::getBridge($this->meethue_token);
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

    public function apply($token = null)
    {
        if ($this->access_method === 'node')
            NodeHue::applyLightStatus($this->light);
        MeetHue::applyLightStatus($this->light, $token);
    }
}
