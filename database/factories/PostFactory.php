<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
{
    protected $model = \App\Models\Post::class;

    public function definition()
    {
        $type = $this->faker->randomElement(
            [
                'text',
                'image',
                'video',
                'text_image',
                'text_video',
            ],
        );

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'content' => in_array(
                $type,
                [
                    'text',
                    'text_image',
                    'text_video',
                ],
            ) ? $this->faker->paragraph : null,
            'image_url' => in_array(
                $type,
                [
                    'image',
                    'text_image',
                ],
            ) ? $this->faker->randomElement(
                [
                    'https://g.aquan.website/storage/app/posts/1.png',
                    'https://g.aquan.website/storage/app/posts/2.png',
                    'https://g.aquan.website/storage/app/posts/3.png',
                ],
            ) : null,
            'video_url' => in_array(
                $type,
                [
                    'video',
                    'text_video',
                ],
            ) ? $this->faker->randomElement(
                [
                    'https://youtu.be/AzCIKEXLteY',
                    'https://www.youtube.com/shorts/zNAi-vocJ6k?feature=share',
                    'https://www.youtube.com/shorts/2wKyd9waGP4?feature=share',
                ],
            ) : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
