<?php

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

  public static function create(HueLight $light)
  {
    return new LightQueryBuilder($light);
  }

  public function setProperty($propertyName, $value)
  {
    $this->light->{$propertyName} = $value;
    return $this;
  }

  public function setProperties(array $values)
  {
    foreach ($values as $key => $value)
      $this->setProperty($key, $value);
    return $this;
  }

  public function apply($token)
  {
    MeetHue::applyLightStatus($this->light, $token);
  }
}
