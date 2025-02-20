<?php

namespace Database\Seeders\job;

use Illuminate\Database\Seeder;
use App\Models\Job\JobSkill;

class JobSkillSeeder extends Seeder
{
    public function run(): void
    {
        JobSkill::factory(200)->create();
    }
}
