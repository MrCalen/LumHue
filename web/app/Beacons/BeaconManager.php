<?php

namespace App\Beacons;

use App\Helpers\HueRedis;
use App\Helpers\MongoHueModel\MongoHueWrapper;
use App\QueryBuilder\LightQueryBuilder;

class BeaconManager
{
    public static function fetchBeaconActions($beaconId)
    {
        $editor = MongoHueWrapper::fetchAndConvertEditor(11);
        $items = $editor->data->items;
        foreach ($items as $item) {
            if (isset($item->lh_id) && $item->lh_id == $beaconId) {
                return $item;
            }
        }
        return null;
    }

    public static function applyBeaconAction($beaconId, $action, $meethue_token, $access_token)
    {
        $beacon = self::fetchBeaconActions($beaconId);
        if (!$beacon) {
            return null;
        }
        $action = $action == 'enter' ? 'enterLight' : 'leaveLight';
        $actionsToApply = $beacon->{ $action };
        foreach ($actionsToApply as $act) {
            $query = LightQueryBuilder::create(abs($act), $meethue_token);
            $query->setProperty('on', $act > 0)
                  ->setProperty('bri', 255)
                  ->setProperty('xy', [0, 0]);
            $l = $query->apply();
            // FIXME: publish with the real color
            // HueRedis::publishLightState($l, $access_token);
        }
    }
}