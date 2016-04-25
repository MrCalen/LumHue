<?php


namespace App\Helpers;


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

        foreach ($records as $key => $record) {
            foreach ($record->status->lights as $lightId => $light) {
                if ($light_id != $lightId) continue;
                $stats[$record->last_updated->timestamp] = $light->state;
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
                '$limit' => 60,
            ];
        else if ($granularity === 'days')
            $lastFilter = [
                '$limit' => 1440,
            ];

        $records = MongoHue::find('bridge', [
            'user_id' => $this->user->id,
        ], $lastFilter);
        return $records;
    }

}