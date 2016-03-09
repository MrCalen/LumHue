<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MeetHue;
use MongoHue;
use App\User;

class UpdateBridge extends Command
{
    protected $signature = 'meethue:bridge';

    protected $description = 'Get Bridge status of Application';

    public function __construct()
    {
        parent::__construct();
    }

    private function getBridge($user)
    {
      $meethue_token = $user->meethue_token;
      $user_id = $user->id;
      $bridge = json_decode(MeetHue::getBridge($meethue_token));
      MongoHue::table('bridge')
        ->updateOne([
          'user_id' => $user_id,
        ], [
          '$set' => [
            'user_id' => $user_id,
            'status' => $bridge,
          ],
        ], [
          'upsert' => true,
        ]);
    }

    public function handle()
    {
      $users = User::all();
      foreach ($users as $user) {
        $this->getBridge($user);
      }
    }
}
