<?php

namespace Database\Factories\Cvs;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cvs\Education;

class EducationFactory extends Factory
{
    protected $model = Education::class;
    public function definition(): array
    {
        return [
            'school' => $this->faker->company . ' School',
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'education_level' => $this->faker->randomElement(
                [
                    'Bachelor',
                    'Diploma',
                    'Master',
                ],
            ),
            'details' => $this->faker->paragraph,
            'grade' => $this->faker->randomElement(
                [
                    'Excellent',
                    'Very Good',
                    'Good',
                ],
            ),
            'institution_type' => $this->faker->randomElement(
                [
                    'governmental',
                    'private',
                ],
            ),
            'city' => $this->faker->city,
        ];
    }
}
