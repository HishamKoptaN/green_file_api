<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Country;
use App\Models\City;

class JobFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(),
            'job_type' => $this->faker->randomElement(['عن بعد', 'من المكتب', 'مختلط']),
            'min_salary' => $this->faker->numberBetween(3000, 7000),
            'max_salary' => $this->faker->numberBetween(8000, 15000),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'SAR']),
            'country_id' => Country::inRandomOrder()->first()->id ?? 1,
            'city_id' => City::inRandomOrder()->first()->id ?? 1,
            'applicants_count' => 0,
        ];
    }
}
