<?php

namespace Database\Factories\job;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job\JobApplication;
use App\Models\Job\Job;
use App\Models\User\User;

class JobApplicationFactory extends Factory
{
    public function definition()
    {
        return [
            'message' => $this->faker->paragraph(),
            'job_id' => $this->faker->numberBetween(
                1,
                25,
            ),
            'user_id' => $this->faker->numberBetween(
                1,
                3,
            ),
        ];
    }
}
