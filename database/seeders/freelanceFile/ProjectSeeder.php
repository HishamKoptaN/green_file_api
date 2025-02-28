<?php

namespace Database\Seeders\freelanceFile;

use Illuminate\Database\Seeder;
use App\Models\FreelanceFile\Project\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::factory(27)->create();
    }
}
