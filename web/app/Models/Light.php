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
     * @throws \Exception
     */
    public function __construct($id, $on, $bri, $effect)
    {
        if (!$id)
            throw new \Exception('Light does not have an ID');

        $this->id = $id;
        $this->on = $on;
        $this->bri = $bri;
        $this->effect = $effect;
        $this->rgb = new RGB(100, 100, 100);
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
        $result = [
            'id' => $this->id,
        ];

        if (isset($this->on))
            $result['on'] = $this->on;
        if (isset($this->bri))
            $result['bri'] = $this->bri;

        if (isset($this->effect))
            $result['effect'] = $this->effect;
        else
            $result['effect'] = 'none';
        return $result;
    }


}