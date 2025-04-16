<?php

namespace Database\Seeders\Cvs;

use Illuminate\Database\Seeder;
use App\Models\Cvs\WorkExperience;
use App\Models\Cvs\Education;
use App\Models\Cvs\Cv;
use App\Models\User\User;

class CVSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(10)->get();
        $users->each(
            function ($user) {
                $cv = Cv::factory()->create(
                    [
                        'user_id' => $user->id,
                    ],
                );
                WorkExperience::factory(
                    rand(
                        1,
                        3,
                    ),
                )->create(
                    [
                        'cv_id' => $cv->id,
                    ],
                );
                Education::factory(
                    rand(
                        1,
                        2,
                    ),

                )->create(
                    [
                        'cv_id' => $cv->id,
                    ],
                );
            },
        );
    }
}
