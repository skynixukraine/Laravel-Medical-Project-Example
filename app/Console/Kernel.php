<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $hostnameMasterServer = config('app.HOSTNAME_MASTER_SERVER');

         $schedule->command('submissions:release')
                  ->everyThirtyMinutes()
                  ->when(function() use ($hostnameMasterServer) { return (gethostname() == $hostnameMasterServer); });

         $schedule->command('reminder:send')
                  ->hourly()
                  ->when(function() use ($hostnameMasterServer) { return (gethostname() == $hostnameMasterServer); });

         $schedule->command('cancelEmails:send')
                  ->everyFiveMinutes()
                  ->when(function() use ($hostnameMasterServer) { return (gethostname() == $hostnameMasterServer); });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
