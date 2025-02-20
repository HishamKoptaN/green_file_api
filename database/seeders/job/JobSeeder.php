<?php

namespace Database\Seeders\job;

use Illuminate\Database\Seeder;
use App\Models\Job\Job;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        Job::factory(25)->create();
    }
}
