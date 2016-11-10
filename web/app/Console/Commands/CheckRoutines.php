<?php

namespace App\Console\Commands;

use App\Helpers\HueRedis;
use App\QueryBuilder\LightQueryBuilder;
use App\User;
use Illuminate\Console\Command;
use MongoHue;
use JWAuth;


class CheckRoutines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routines:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for routines update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set('Europe/Paris');
        $routines = MongoHue::getRoutines();
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
        }
    }
}
