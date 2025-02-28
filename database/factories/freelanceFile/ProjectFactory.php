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
            'title' => $this->faker->randomElement([
                'برمجة',
                'اوتو كاد',
                'تسويق',
                'موشن جرافيك',
                'جرافيك',
                'ادارة',
            ]),
            'image' => $this->faker->randomElement([
                'https://g.aquan.website/storage/app/projects/1.png',
                'https://g.aquan.website/storage/app/projects/2.png',
                'https://g.aquan.website/storage/app/projects/3.png',
            ]),
            'description' => $this->faker->paragraph,
            'specialization_id' => $this->faker->numberBetween(
                1,
                10,
            ),
        ];
    }
}
[];
