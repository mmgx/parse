<?php

namespace App\Console;

use App\Console\Commands\GetCategoriesInfo;
use App\Console\Commands\GetMarkaInfo;
use App\Console\Commands\GetMarkaInfo1;
use App\Console\Commands\GetMarkaInfo2;
use App\Console\Commands\GetRazmenInfo;
use App\Console\Commands\GetSubcategoriesInfo;
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
        GetMarkaInfo::class,
        GetCategoriesInfo::class,
        GetSubcategoriesInfo::class,
        GetRazmenInfo::class,
        GetMarkaInfo1::class,
        GetMarkaInfo2::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
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
