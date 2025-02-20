<?php

namespace Database\Factories\job;

use Illuminate\Database\Eloquent\Factories\Factory;

class JobSkillFactory extends Factory
{
    public function definition()
    {
        return [
            'job_id' =>  $this->faker->numberBetween(
                1,
                25,
            ),
            'skill_id' => $this->faker->numberBetween(
                1,
                80,
            ),
        ];
    }
}
