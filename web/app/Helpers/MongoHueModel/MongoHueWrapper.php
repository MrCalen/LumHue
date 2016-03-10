<?php

namespace App\Helpers\MongoHueModel;

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
        ]
      ]);
  }
}

?>
