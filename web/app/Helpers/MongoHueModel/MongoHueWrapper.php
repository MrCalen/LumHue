<?php

namespace App\Helpers\MongoHueModel;

use MongoHue;

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
        ], [
          'upsert' => true,
        ]
      ]);
  }
}

?>
