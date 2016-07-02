<?php
declare(strict_types=1);

namespace App\Helpers\MongoHueModel;

use App\Helpers\Mongo\Utils;

use App\Models\HueLight;
use MongoHue;
use DataTime;

/**
 * Class MongoHueWrapper
 * @package App\Helpers\MongoHueModel
 *
 * Wrapper around Mongo
 */
class MongoHueWrapper
{
    /**
     * Fetches a new bridge status
     *
     * @param $bridge : Data of the fetch
     * @param $user_id : Id of the User
     *
     */
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

    /**
     * Returns a light from Mongo
     *
     * @param $user_id : Id of the User
     * @param $light_id : Id of the lamp
     * @return HueLight : instance of the lights
     * @throws \Exception : If light does not exists
     */
    public static function retrieveLight($user_id, $light_id) : HueLight
    {
        $lights = MongoHueWrapper::retrieveLightStates($user_id);
        if (!isset($lights[$light_id])) {
            throw new \Exception('Light does not exist');
        }
        return $lights[$light_id];
    }

    /**
     * Fetches all the lights from Mongo
     *
     * @param $user_id : Id of the User
     * @return array : all the lights
     * @throws \Exception : If light does not exists
     */
    public static function retrieveLightStates($user_id) : array
    {
        $bridgeStatus = MongoHue::table('bridge')->find([ 'user_id' => $user_id ]);
        $bridgeStatus = Utils::mongoArray($bridgeStatus);
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

    public static function updateEditor($data, $user_id)
    {
        MongoHue::table('editor')->updateOne([
            'user_id' => $user_id,
        ], [
            '$set' => [
                'user_id' => $user_id,
                'data' => $data,
            ],
        ], [
            'upsert' => true,
        ]);
    }

    public static function findEditor($user_id)
    {
        return MongoHue::table('editor')
            ->findOne([
                'user_id' => $user_id,
            ]);
    }

    public static function fetchAndConvertEditor($user_id)
    {
        $editor = MongoHueWrapper::findEditor($user_id);
        if (!$editor) {
            return null;
        }

        $editor = \MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($editor));
        $editor = json_decode($editor);
        return $editor;
    }
}
