<?php

namespace Database\Factories\freelanceFile;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FreelanceFile\Project\Project;

class ProjectFactory extends Factory
{
    protected $model = Project::class;
    public function definition()
    {
        return [
            'user_id' =>  $this->faker->numberBetween(
                1,
                5,
            ),
            'title' => $this->faker->randomElement(
                [
                    'برمجة',
                    'اوتو كاد',
                    'تسويق',
                    'موشن جرافيك',
                    'جرافيك',
                    'ادارة',
                ],
            ),
            'image' => $this->faker->randomElement(
                [
                    env('APP_URL') . '/public/media/projects/1.png',
                    env('APP_URL') . '/public/media/projects/2.png',
                    env('APP_URL') . '/public/media/projects/3.png',
                ],
            ),
            'description' => $this->faker->paragraph,
            'specialization_id' => $this->faker->numberBetween(
                1,
                10,
            ),
        ];
    }
}
[];
