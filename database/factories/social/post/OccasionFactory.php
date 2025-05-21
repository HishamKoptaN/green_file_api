<?php

namespace Database\Factories\Social\Post;

use App\Models\Social\Post\Occasion;
use Illuminate\Database\Eloquent\Factories\Factory;

class OccasionFactory extends Factory
{
    protected $model = Occasion::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'link' => $this->faker->url,
            'start_at' => $this->faker->dateTimeBetween('now', '+1 week'),
            'end_at' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'image' => $this->faker->randomElement(
                [
                    'https://res.cloudinary.com/dqzu6ln2h/image/upload/v1741171126/samples/cup-on-a-table.jpg',
                    'https://res.cloudinary.com/dqzu6ln2h/image/upload/v1741171124/samples/balloons.jpg',
                ],
            ),


        ];
    }
}
