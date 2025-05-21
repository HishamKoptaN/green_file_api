<?php

namespace Database\Factories\Social\Post;

use App\Models\Social\Post\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollPostFactory extends Factory
{
    protected $model = Poll::class;

    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence,
            'end_date' => now()->addDays(3),
        ];
    }
}
