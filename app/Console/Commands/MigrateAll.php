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

            'database/migrations/businessFile',
            'database/migrations/chats',
            'database/migrations/courses',
            'database/migrations/cvs',
            'database/migrations/freelanceFile/projects',
            'database/migrations/jobs',
            'database/migrations/locations',
            'database/migrations/notifications',
            'database/migrations/powers',
            'database/migrations/social/posts',
            'database/migrations/social/statuses',
            'database/migrations/users',
        ];

        foreach ($folders as $folder) {
            $this->info("📂 Running migrations for: $folder");
            Artisan::call('migrate', ['--path' => $folder, '--force' => true]);
            $this->info(Artisan::output());
        }

        $this->info('✅ All migrations completed successfully!');
    }
}
