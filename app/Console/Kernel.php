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
        '\App\Console\Commands\JobsDownloadVoip',
        '\App\Console\Commands\JobsProcessReport',
        '\App\Console\Commands\JobsProcessImportCheckin',
        '\App\Console\Commands\JobsProcessLockParent',
        '\App\Console\Commands\JobsProcessDataImport',
        '\App\Console\Commands\JobsDownloadCdrPA',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('JobsDownloadCdrPA:command')->cron('*/15 * * * *');
        // $schedule->command('jobsDownloadVoip:command')->cron('*/30 * * * *');
        $schedule->command('jobsProcessReport:command')->everySixHours();
        // $schedule->command('jobsProcessImportCheckin:command')->cron('*/3 * * * *');
        $schedule->command('jobsProcessLockParent:command')->cron('0 0 * * *');
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
