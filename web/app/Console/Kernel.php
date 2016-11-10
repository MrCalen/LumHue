<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateBridge::class,
        Commands\WebSocketServer::class,
        Commands\WebSocketAudioServer::class,
        Commands\CheckRoutines::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('meethue:bridge')
                 ->everyMinute()
                 ->sendOutputTo('/tmp/hue.log')
                 ;
    }
}
