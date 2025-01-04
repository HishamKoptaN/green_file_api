<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\AddDepositsToBalance::class,
        \App\Console\Commands\SetUserActive::class,
        \App\Console\Commands\SetUserInactive::class,
    ];
    protected function schedule(
        Schedule $schedule,
    ) {
        $schedule->command('deposits:add-to-available-balance')->everyMinute();
        $schedule->command('user:set-active')->everyMinute();
        $schedule->command('user:set-inactive')->everyMinute();
        // ->hourly();
    }
}
