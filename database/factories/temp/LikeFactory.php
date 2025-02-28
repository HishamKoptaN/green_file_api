<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Social\Post\Like;
use App\Models\Social\Post\Post;


class LikeFactory extends Factory
{
    protected $model = Like::class;
    public function definition()
    {
        return [
            'likeable_id' => Post::inRandomOrder()->first()->id ?? Post::factory(),
            'likeable_type' => Post::class,
            'user_id' => rand(1, 5),
        ];
    }
}
