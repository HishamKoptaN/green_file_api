<?php
namespace Database\Factories\Cvs;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkExperienceFactory extends Factory
{
    public function definition()
    {
        return [
            'position' => $this->faker->jobTitle,
            'job_title' => $this->faker->jobTitle,
            'company_name' => $this->faker->company,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'address' => $this->faker->address,
            'details' => $this->faker->paragraph,
        ];
    }
}
