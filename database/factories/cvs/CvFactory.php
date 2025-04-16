<?php

namespace Database\Factories\Cvs;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\User;

class CvFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'image' => $this->faker->imageUrl(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'job_title' => $this->faker->jobTitle,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'address' => $this->faker->address,
            'birthdate' => $this->faker->date(),
            'marital_status' => $this->faker->randomElement(
                [
                    'single',
                    'married',
                    'divorced',
                ],
            ),
            'gender' => $this->faker->randomElement(
                [
                    'male',
                    'female',
                ],
            ),
            'nationality' => $this->faker->country,
        ];
    }
}
