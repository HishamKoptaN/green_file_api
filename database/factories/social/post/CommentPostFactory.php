<?php

namespace Database\Factories\social\post;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Social\Post\PostComment;


class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;
    public function definition()
    {
        return [
            'post_id' => rand(1, 15),
            'user_id' => rand(1, 3),
            'comment' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
