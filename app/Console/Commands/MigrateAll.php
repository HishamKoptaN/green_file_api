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
        $this->info('🚨 Dropping all tables...');
        Artisan::call('db:wipe', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('🚀 Running all migrations...');
        Artisan::call('migrate', ['--force' => true]);
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
            'database/migrations/freelanceFile',
            'database/migrations/freelanceFile/projects',
            'database/migrations/businessFile',
            'database/migrations/businessFile/opinionPolls',
        ];

        foreach ($folders as $folder) {
            $this->info("📂 Running migrations for: $folder");
            Artisan::call('migrate', ['--path' => $folder, '--force' => true]);
            $this->info(Artisan::output());
        }

        $this->info('✅ All migrations completed successfully!');
    }
}
