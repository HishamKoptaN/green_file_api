<?php

namespace Database\Factories\social\post;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\User;
use App\Models\Social\Post\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

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
        $postType = $this->faker->randomElement(
            [
                'social',
                'news',
                'company',
            ],
        );
        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        if ($postType === 'company' || $postType === 'news') {
            $user = User::whereIn('id', [11, 12, 13, 14, 15])->inRandomOrder()->first();
        } else {
            $user = User::whereIn('id', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,])->inRandomOrder()->first();
        }
        $isSharedPost = $this->faker->boolean(30);
        $originalPostId = null;
        if ($isSharedPost) {
            $originalPost = Post::where('user_id', '!=', $user->id)->inRandomOrder()->first();
            if (!$originalPost) {
                $originalPost = Post::inRandomOrder()->first();
            }
            if (!$originalPost) {
                $originalPost = Post::factory()->create();
            }
            $originalPostId = $originalPost->id;
            $type = 'text';
            $comment = $this->faker->sentence;
        }

        return [
            'user_id' => $user->id,
            'content' => !$isSharedPost && in_array($type, ['text', 'text_image', 'text_video'])
                ? $this->faker->paragraph
                : null,
            'image_url' => !$isSharedPost && in_array($type, ['image', 'text_image'])
                ? $this->faker->randomElement(
                    [
                        env('APP_URL') . '/public/media/posts/1.png',
                        env('APP_URL') . '/public/media/posts/2.png',
                        env('APP_URL') . '/public/media/posts/3.png',
                    ],
                )
                : null,
            'video_url' => !$isSharedPost && in_array(
                $type,
                [
                    'video',
                    'text_video',
                ],
            )
                ? $this->faker->randomElement(
                    [
                        'https://youtu.be/AzCIKEXLteY',
                        'https://www.youtube.com/shorts/zNAi-vocJ6k?feature=share',
                        'https://www.youtube.com/shorts/2wKyd9waGP4?feature=share',
                    ],
                )
                : null,
            'original_post_id' => $originalPostId,
            'type' => $postType,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
