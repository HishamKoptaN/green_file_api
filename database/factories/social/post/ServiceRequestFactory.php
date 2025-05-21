<?php

namespace Database\Factories\Social\Post;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'specialization_id' => rand(1, 10),
            'details' => $this->faker->paragraph,
        ];
    }
}
