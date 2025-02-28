<?php

namespace Database\Seeders\BusinessFile;

use Illuminate\Database\Seeder;
use App\Models\BusinessFile\Service;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        Service::factory()->count(50)->create();
    }
}
