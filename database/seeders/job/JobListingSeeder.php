<?php

namespace Database\Seeders\job;

use App\Models\Job\JobListing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{

    public function run()
    {
        JobListing::factory(20)->create();
    }
}
