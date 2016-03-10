<?php

namespace app\Models;

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

  public function __construct($id, $light)
  {
    $this->id = $id;
    foreach ($light->state as $key => $value)
      $this->{$key} = $value;
    $this->name = $light->name;
  }

  public function setProperty()
{}

  public function toArray()
  {
    $result = [];

    if ($this->on != null)
      $result['on'] = json_decode($this->on);
    if (isset($this->bri))
      $result['bri'] = $this->bri;

    if (isset($this->effect))
      $result['effect'] = $this->effect;
    else
      $result['effect'] = 'none';

    return $result;
  }


}