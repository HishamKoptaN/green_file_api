<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::factory(75)->create();
    }
}
