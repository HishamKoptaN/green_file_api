<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;
use App\Models\Skill;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        Skill::factory(25)->create();
        Job::factory(20)->create()->each(
            function ($job) {
                $skills = Skill::inRandomOrder()->limit(3)->pluck('id');
                $job->skills()->attach(
                    $skills,
                );
            },
        );
    }
}
