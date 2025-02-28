<?php

namespace Database\Seeders\BusinessFile;
use Illuminate\Database\Seeder;
use App\Models\BusinessFile\Training;

class TrainingSeeder extends Seeder
{
    public function run()
    {
        Training::factory()->count(30)->create();
    }
}
