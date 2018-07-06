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
        '\App\Console\Commands\UpdateCryptoCap',
        '\App\Console\Commands\UpdateExpiredAddresses',
        '\App\Console\Commands\UpdateTransactions',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('NextExchange:updatecryptocap')->everyMinute();
        $schedule->command('NextExchange:updateexpiredaddresses')->hourly();
        //$schedule->command('NextExchange:updatetransactions')->everyMinute(); // @TODO: (18052018) Do good test, before activating! LYL date is still problem
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
