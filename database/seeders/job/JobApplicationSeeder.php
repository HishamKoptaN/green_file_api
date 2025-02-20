<?php

namespace Database\Seeders\job;

use Illuminate\Database\Seeder;
use App\Models\Job\JobApplication;

class JobApplicationSeeder extends Seeder
{
    public function run()
    {
        JobApplication::factory(75)->create();
    }
}
