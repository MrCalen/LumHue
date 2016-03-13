<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateBridge::class,
        Commands\WSServer::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('meethue:bridge')
                 ->hourly()
                 ->sendOutputTo('/tmp/hue.log')
                 ;
    }
}
