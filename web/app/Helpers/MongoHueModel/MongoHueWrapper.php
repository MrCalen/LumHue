<?php
declare(strict_types=1);

namespace App\Helpers\MongoHueModel;

use App\Helpers\Mongo\Utils;

use App\Models\HueLight;
use MongoHue;
use DataTime;

class MongoHueWrapper
{
    public static function updateBridgeStatus($bridge, $user_id)
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

    public static function retrieveLight($user_id, $light_id) : HueLight
    {
        $lights = MongoHueWrapper::RetrieveLightStates($user_id);
        if (!isset($lights[$light_id])) {
            throw new \Exception('Light does not exist');
        }
        return $lights[$light_id];
    }

    public static function retrieveLightStates($user_id) : array
    {
        $bridgeStatus = MongoHue::table('bridge')->find([ 'user_id' => $user_id ]);
        $bridgeStatus = Utils::MongoArray($bridgeStatus);
        if (!count($bridgeStatus) | !isset($bridgeStatus[0]->status->lights)) {
            throw new \Exception('No bridge found');
        }

        $bridgeStatus = $bridgeStatus[0];
        $lights = $bridgeStatus->status->lights;
        $convertedLights = [];
        foreach ($lights as $index => $light) {
            $convertedLights[$index] = new HueLight($index, $light);
        }

        return $convertedLights;
    }
}
