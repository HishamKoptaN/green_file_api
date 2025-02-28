<?php

namespace Database\Factories\BusinessFile;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\OpportunityLooking;
use App\Models\BusinessFile\Service;

class ServiceFactory extends Factory
{
    protected $model = Service::class;
    public function definition()
    {
        return [
            'user_id' => OpportunityLooking::inRandomOrder()->first()->id ?? 1,
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 30),
            'image' => $this->faker->randomElement(
                [
                    'https://g.aquan.website/storage/app/services/1.jpeg',
                    'https://g.aquan.website/storage/app/services/2.jpeg',
                    'https://g.aquan.website/storage/app/services/3.jpeg',
                ],
            ),
        ];
    }
}
