<?php


namespace App\Jobs;

use App\Helpers\LumHueColorConverter;
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

    public function __construct($ambiance_id, $user)
    {
        $this->user = $user;
        $this->ambiance = MongoHue::find('ambiance', [
            '_id' => new \MongoDB\BSON\ObjectID($ambiance_id),
            'user_id' => $user->id,
        ]);
    }

    public function handle()
    {
        foreach ($this->ambiance->{0}->ambiance->lights as $step) {
            $sleep_time = $step->duration;
            foreach ($step->lightscolors as $light) {
                $builder = LightQueryBuilder::create($light->id, $this->user->meethue_token);
                $hueLight = new HueLight($light->id, new \stdClass());
                $light_color = LumHueColorConverter::RGBstrToRGB($light->color);
                $light_color = LumHueColorConverter::RGBtoChromatic($light_color[0],$light_color[1], $light_color[2]);
                $xy = [
                    $light_color['x'],
                    $light_color['y'],
                ];
                $hueLight->setProperty('xy', $xy);
                $hueLight->setProperty('on', $light->on);
                $builder->setLight($hueLight);
                $builder->apply();
            }
            echo 'finished one step';
            sleep($sleep_time);
        }
    }
}