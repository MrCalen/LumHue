<?php
declare(strict_types=1);

namespace app\Models;

use stdClass;

class HueLight
{
    protected $on;
    protected $bri;
    protected $hue;
    protected $sat;
    protected $effect;
    protected $xy;
    protected $ct;
    protected $alert;
    protected $colormode;
    protected $reachable;

    protected $name;
    protected $id;

    public function __construct(string $id, stdClass $light)
    {
        $this->id = $id;
        foreach ($light->state as $key => $value) {
            $this->{$key} = $value;
        }
        $this->name = $light->name;
    }

    public function setProperty(string $property, $value)
    {
        $this->{$property} = $value;
    }

    public function toArray() : array
    {
        $result = [];

        if ($this->on != null) {
            $result['on'] = json_decode($this->on);
        }
        if (isset($this->bri)) {
            $result['bri'] = $this->bri;
        }

        if (isset($this->effect)) {
            $result['effect'] = $this->effect;
        } else {
            $result['effect'] = 'none';
        }

        return $result;
    }
}
