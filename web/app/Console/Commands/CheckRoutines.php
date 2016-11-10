<?php

namespace App\Console\Commands;

use App\Helpers\HueRedis;
use App\Helpers\MongoHueModel\MongoHueWrapper;
use App\QueryBuilder\LightQueryBuilder;
use App\User;
use Illuminate\Console\Command;
use MongoDB\BSON\ObjectID;
use MongoHue;
use JWAuth;


class CheckRoutines extends Command
{
    protected $signature = 'routines:check';

    protected $description = 'Check for routines update';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        date_default_timezone_set('Europe/Paris');
        $routines = MongoHueWrapper::getRoutines(true);
        $users = User::all()->all();
        $users = array_reduce($users, function ($new, $user) {
            $new[$user->id] = $user;
            return $new;
        }, []);

        $time = time();
        $currentDay = date("N", $time);
        $currentHour = date("H", $time);
        $currentMin = date("i");


        foreach ($routines as $routine)
        {
            $user = $users[$routine->user_id];
            $meethue_token = $user->meethue_token;

            $isDay = in_array($currentDay, $routine->days);
            $isHour = $currentHour == $routine->h;
            $isMin = $currentMin == $routine->m;

            if (!$isDay || !$isHour || !$isMin) continue;

            foreach ($routine->lights as $light) {
                $light_id = $light->light_id;
                $on = $light->status == 1;
                $light = LightQueryBuilder::create($light_id, $meethue_token)
                    ->setProperty('on', $on)
                    ->apply();
            }

            if (!$routine->rec) {
                MongoHue::table('routines')->deleteOne([
                    '_id' => new ObjectID($routine->_id->{'$oid'}),
                ]);
            }
        }
    }
}
