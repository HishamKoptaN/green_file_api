<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // الحصول على كل ملفات Seeder داخل `database/seeders`
        $files = File::allFiles(database_path('seeders'));

        foreach ($files as $file) {
            // الحصول على اسم الـ Seeder بدون الامتداد
            $seeder = pathinfo($file, PATHINFO_FILENAME);

            // استثناء `DatabaseSeeder` نفسه حتى لا يتم استدعاؤه مرة أخرى
            if ($seeder !== 'DatabaseSeeder') {
                $this->call($seeder);
            }
        }
    }
}
