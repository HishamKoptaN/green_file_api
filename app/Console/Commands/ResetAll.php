<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetAll extends Command
{
    protected $signature = 'reset:all';
    protected $description = 'Drop all tables and run all migrations (including subdirectories)';

    public function handle()
    {
        $this->info('Dropping all tables...');
        Artisan::call('db:wipe', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('Running all migrations...');
        Artisan::call('migrate:all');
    }

}
