<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{




    public function definition()
    {
        return [
            'user_id' =>  $this->faker->numberBetween(
                1,
                3,
            ),
            'job_title' => $this->faker->randomElement(
                'مطور ويب',
                'مطور فلاتر',
                'مطور اندرويد',
                'محلل بيانات',
                'محلل بيانات',
                'مسوق رقمي',
                'مهندس معماري',
            ),
            'description' => $this->faker->paragraph,
            'specialization_id' => $this->faker->numberBetween(
                1,
                80,
            ),
        ];
    }
}
[];
