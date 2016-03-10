<?php

namespace App\Helpers\MongoHueModel;

use App\Helpers\Mongo\Utils;

use App\Models\HueLight;
use MongoHue;
use DataTime;

class MongoHueWrapper
{
  public static function UpdateBridgeStatus($bridge, $user_id)
  {
    MongoHue::table('bridge')
      ->update([
        'user_id' => $user_id,
      ], [
        '$set' => [
          'status' => $bridge,
          'last_updated' => [
            'date' => date("F j, Y, g:i a"),
            'timestamp' => time(),
          ],
        ], [
          'upsert' => true,
        ],
      ]);
  }

  public static function RetrieveLight($user_id, $light_id)
  {
    $lights = MongoHueWrapper::RetrieveLightStates($user_id);
    if (!isset($lights[$light_id]))
      throw new \Exception('Light does not exist');
    return $lights[$light_id];
  }

  public static function RetrieveLightStates($user_id)
  {
    $bridgeStatus = MongoHue::table('bridge')->find([ 'user_id' => $user_id ]);
    $bridgeStatus = Utils::MongoArray($bridgeStatus);
    if (!count($bridgeStatus) | !isset($bridgeStatus[0]->status->lights))
      throw new \Exception('No bridge found');
    $bridgeStatus = $bridgeStatus[0];
    $lights = $bridgeStatus->status->lights;
    $convertedLights = [];
    foreach ($lights as $index => $light)
      $convertedLights[$index] = new HueLight($index, $light);

    return $convertedLights;
  }
}

?>
