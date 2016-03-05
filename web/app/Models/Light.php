<?php

namespace App\Models;

class Light
{
    protected $id;
    protected $on;
    protected $bri;
    protected $effect;

    protected $rgb;

    /**
     * Light constructor.
     * @param $id
     * @param bool $on
     * @param string $bri
     * @param string $effect
     * @param \App\Models\RGB $rgb
     */
    public function __construct($id, $on = true, $bri = "255", $effect = "none", RGB $rgb)
    {
        $this->id = $id;
        $this->on = $on;
        $this->bri = $bri;
        $this->effect = $effect;
        $this->rgb = isset($rgb) ? $rgb : new RGB(255, 255, 255);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOn()
    {
        return $this->on;
    }

    /**
     * @param mixed $on
     */
    public function setOn($on)
    {
        $this->on = $on;
    }

    /**
     * @return mixed
     */
    public function getBri()
    {
        return $this->bri;
    }

    /**
     * @param mixed $bri
     */
    public function setBri($bri)
    {
        $this->bri = $bri;
    }

    /**
     * @return mixed
     */
    public function getEffect()
    {
        return $this->effect;
    }

    /**
     * @return \App\Models\RGB
     */
    public function getRgb()
    {
        return $this->rgb;
    }

    /**
     * @param \App\Models\RGB $rgb
     */
    public function setRgb($rgb)
    {
        $this->rgb = $rgb;
    }

    /**
     * @param mixed $effect
     */
    public function setEffect($effect)
    {
        $this->effect = $effect;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'on' => $this->on,
            'bri' => $this->bri,
            'effect' => $this->effect,
        ];
    }


}