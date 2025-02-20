<?php

namespace Database\Seeders\job;

use Illuminate\Database\Seeder;
use App\Models\Job\Specialization;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        Specialization::factory()->count(10)->create();
    }
}
