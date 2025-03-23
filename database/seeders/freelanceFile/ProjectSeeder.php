<?php

namespace Database\Seeders\FreelanceFile;

use Illuminate\Database\Seeder;
use App\Models\FreelanceFile\Project\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::factory(25)->create();
    }
}
