<?php

namespace Database\Factories\Social\Post;

use App\Models\Social\Post\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'postable_type' => null,
            'postable_id' => null,
        ];
    }
}
