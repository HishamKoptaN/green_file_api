<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MigrateFreshAll extends Command
{
    protected $signature = 'migrate:fresh-all';
    protected $description = 'Drop all tables, re-run migrations from subdirectories';

    public function handle()
    {
        // تنفيذ fresh لحذف كل الجداول وإعادة إنشائها
        $this->info('Refreshing database...');
        Artisan::call('migrate:fresh');
        $this->info(Artisan::output());

        // مسار المجلد الأساسي للهجرات
        $migrationsPath = base_path('database/migrations');
        $migrationFiles = File::allFiles($migrationsPath); // جلب جميع ملفات الهجرة داخل كل المجلدات الفرعية

        foreach ($migrationFiles as $file) {
            $relativePath = str_replace(base_path() . '/', '', $file->getPathname());
            $this->info("Running migration for: $relativePath");
            Artisan::call('migrate', ['--path' => $relativePath]);
            $this->info(Artisan::output());
        }

        $this->info('All migrations have been executed successfully!');
    }
}
