<?php

namespace Database\Factories\Social\Post;

use App\Models\Social\Post\SharedPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Social\Post\Post;

class SharedPostFactory extends Factory
{
    protected $model = SharedPost::class;

    public function definition(): array
    {
        $post = Post::inRandomOrder()->first()
            ?? Post::factory()->create();

        return [
            'post_id' => $post->id,
            'content' => $this->faker->sentence,
        ];
    }
}
