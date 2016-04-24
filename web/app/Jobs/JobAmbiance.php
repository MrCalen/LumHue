<?php


namespace App\Jobs;

use App\Helpers\MongoHue;
use app\Models\HueLight;
use App\QueryBuilder\LightQueryBuilder;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobAmbiance extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    protected $ambiance;
    protected $user;

    public function __construct($ambiance_id, $user) {
        $this->user = $user;
        $this->ambiance = MongoHue::find('ambiance', [
            '_id' => new \MongoDB\BSON\ObjectID($ambiance_id),
            'user_id' => $user->id,
        ]);
    }

    public function handle()
    {

        // Json looks like:
        // { "name" : "coucou",
        //   "lights" : [
        //       "5": {
        //          "1" : {
        //              "color": white
        //          }
        //      }, "180": {
        //      }
        //   ]
        // }
        foreach ($this->ambiance->lights as $sleep_time => $step) {
            foreach ($step as $light_id => $light) {
                $builder = LightQueryBuilder::create($light, $this->user->meethue_token);
                $builder->setLight(new HueLight($light->id, $light));
                $builder->apply();
            }
            sleep($sleep_time);
        }
    }
}