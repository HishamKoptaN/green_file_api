<?php

namespace Database\Factories\social\post;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Social\Post\Comment;
use App\Models\Social\Post\Post;

class CommentFactory extends Factory
{
    protected $model = Comment::class;
    public function definition()
    {
        return [
            'commentable_id' => Post::inRandomOrder()->first()->id ?? Post::factory(),
            'commentable_type' => Post::class,
            'user_id' => rand(1, 5),
            'comment' => $this->faker->sentence,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
