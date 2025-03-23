<?php

namespace Database\Seeders\BusinessFile;

use Illuminate\Database\Seeder;
use App\Models\BusinessFile\News;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        News::factory(25)->create();
    }
}
