<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MeetHue;
use MongoHue;
use App\User;
use DataTime;

class UpdateBridge extends Command
{
    protected $signature = 'meethue:bridge';

    protected $description = 'Update Bridge status of Application';

    public function __construct()
    {
        parent::__construct();
    }

    private function getBridge($user)
    {
        $meethue_token = $user->meethue_token;
        if (!$meethue_token) {
            return;
        }

        date_default_timezone_set('Europe/Paris');
        $user_id = $user->id;
        $bridge = json_decode(MeetHue::getBridge($meethue_token));
        MongoHue::table('bridge')
            ->insertOne([
                'user_id' => $user_id,
                'status' => $bridge,
                'last_updated' => [
                    'date' => date("F j, Y, g:i a"),
                    'timestamp' => time(),
                ],
            ]);
    }

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $this->getBridge($user);
        }

        echo 'Updated Bridge';

    }
}
