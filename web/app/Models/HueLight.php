<?php
declare(strict_types=1);

namespace App\Models;

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

    public function __construct($id, stdClass $light)
    {
        $this->id = $id;
        if (count((array)$light)) {
            foreach ($light->state as $key => $value) {
                $this->{ $key } = $value;
            }
            $this->name = $light->name;
        }
    }

    public function setProperty(string $property, $value)
    {
        if ($value !== null) {
            $this->{$property} = $value;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function toArray() : array
    {
        $result = [];

        if (isset($this->on)) {
            $result['on'] = $this->on;
        }

        if (isset($this->bri)) {
            $result['bri'] = $this->bri;
        }

        if (isset($this->effect)) {
            $result['effect'] = $this->effect;
        }
        if (isset($this->xy)) {
            $result['xy'] = $this->xy;
        }

        return $result;
    }

    public function getColor() : array
    {
        return [
            'bri' => $this->bri,
            'x' => $this->xy['x'],
            'y' => $this->xy['y'],
        ];
    }
}
