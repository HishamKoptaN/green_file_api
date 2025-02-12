<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'Flutter',
                'Kotlin',
                'Data Analysis',
                'Graphic Design',
                'Marketing',
                'Laravel',
                'React Native',
                'UI/UX Design'
            ]),
        ];
    }
}
