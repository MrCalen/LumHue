<?php
declare(strict_types=1);

namespace App\QueryBuilder;

use App\Models\HueLight;
use NodeHue;
use MongoHue;
use MeetHue;
use Auth;

class LightQueryBuilder
{
    protected $light;
    protected $access_method = 'node';
    protected $meethue_token = null;

    private function __construct($light_id = null, $meethue_token = null)
    {
        $this->meethue_token = $meethue_token;
        if (!Auth::user() || !Auth::user()->bridge_addr) {
            $this->access_method = 'meethue';
            if (!$light_id) {
                $light_id = '';
            }
            $this->light = new HueLight($light_id, (object)[]);
            return;
        }

        if ($light_id === null) {
            return;
        }

        $lights = json_decode(NodeHue::getBridgeStatus());
        $light = $lights->lights[$light_id];
        $this->light = new HueLight($light_id, $light);
    }

    public static function create($light_id, $meethue_token) : LightQueryBuilder
    {
        return new LightQueryBuilder($light_id, $meethue_token);
    }

    public function setLight(HueLight $light) : LightQueryBuilder
    {
        $this->light = $light;
    }

    public function viaMeetHue() : LightQueryBuilder
    {
        $this->access_method = 'meethue';
    }

    public function getBridgeState()
    {
        if ($this->access_method === 'node') {
            return NodeHue::getBridgeStatus();
        }
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

    public function apply()
    {
        if ($this->access_method === 'node') {
            NodeHue::applyLightStatus($this->light);
            return;
        }
        MeetHue::applyLightStatus($this->light, $this->meethue_token);
    }
}
