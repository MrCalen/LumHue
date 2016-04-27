<?php


namespace App\Helpers;

use MongoHue;

class StatsManager
{

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param $granularity : references either hour, day, or all time
     * @param $light_id : light id
     * @return array
     */
    public function lightStats($granularity, $light_id)
    {
        $records = $this->getRecord($granularity);
        $stats = [];
        $i = 0;

        foreach ($records as $key => $record) {
            if (!isset($record->status->lights)) continue;
            foreach ($record->status->lights as $lightId => $light) {
                if ($light_id != $lightId) continue;
                $stats[$record->last_updated->timestamp] = $light->state;
                ++$i;
            }
        }
        return $stats;
    }

    /**
     * @param $granularity
     * @return array : timestamp with bridge status
     */
    public function bridgeStats($granularity) {
        $records = $this->getRecord($granularity);
        $stats = [];
        foreach ($records as $key => $record) {
            $stats[$record->last_updated->timestamp] = $record->status->bridgeIsOnline;
        }
        return $stats;
    }

    /**
     * @param $granularity
     * @return mixed : fetches a number of record according to granularity
     */
    private function getRecord($granularity) {
        $lastFilter = [];
        if ($granularity === 'hours')
            $lastFilter = [
                'limit' => 60,
                'sort' => [
                    '_id' => -1,
                ],
            ];
        else if ($granularity === 'days')
            $lastFilter = [
                'limit' => 1440,
                'sort' => [
                    '_id' => -1,
                ],
            ];

        $records = MongoHue::table('bridge')->find([
            'user_id' => $this->user->id,
        ], $lastFilter);
        return $records;
    }

    public function history() {
        $record = MongoHue::table('history')->find([
            'user_id' => $this->user->id,
        ], [
            'limit' => 10,
            'sort' => [
                '_id' => -1,
            ],
        ]);
        return $record;
    }

    public function weather($lat, $long) {
        $token = env('WEATHER_API_KEY');

        $base_uri = 'api.openweathermap.org/data/2.5/forecast?APPID=' . $token . '&lat=' . $lat . '&lon=' . $long;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $base_uri);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}