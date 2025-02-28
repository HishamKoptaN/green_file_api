<?php

namespace Database\Seeders\BusinessFile;

use Illuminate\Database\Seeder;
use App\Models\BusinessFile\CompanyPost;

class CompanyPostSeeder extends Seeder
{
    public function run(): void
    {
        CompanyPost::factory(25)->create();
    }
}
