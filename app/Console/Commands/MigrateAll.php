<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateAll extends Command
{
    protected $signature = 'migrate:all';
    protected $description = 'Run all migration files including subdirectories';
    public function handle()
    {
        $this->info("Resetting database...");
        Artisan::call('migrate:fresh', ['--drop-views' => true, '--drop-types' => true]);
        $this->info(Artisan::output());
        $folders = [
            'database/migrations/locations',
            'database/migrations/users',
            'database/migrations/powers',
            'database/migrations/jobs',
            'database/migrations/skills',
            'database/migrations/courses',
            'database/migrations/projects',
            'database/migrations/social/statuses',
            'database/migrations/social/posts',
            'database/migrations/businessFile',
        ];

        foreach ($folders as $folder) {
            $this->info("Running migrations for: $folder");
            Artisan::call('migrate', ['--path' => $folder]);
            $this->info(Artisan::output());
        }
    }
}
