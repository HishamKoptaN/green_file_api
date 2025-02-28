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
        $type = $this->faker->randomElement([
            'text',
            'image',
            'video',
            'text_image',
            'text_video',
        ]);

        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        // 30% احتمال أن يكون المنشور "مشاركة"
        $isSharedPost = $this->faker->boolean(30);
        $originalPostId = null;
        $comment = null;
        if ($isSharedPost) {
            // حاول تجيب منشور لمستخدم آخر، ولو ما لقيت خد أي منشور حتى لو لنفس المستخدم
            $originalPost = Post::where('user_id', '!=', $user->id)->inRandomOrder()->first();

            if (!$originalPost) {
                $originalPost = Post::inRandomOrder()->first();
            }
            if (!$originalPost) {
                // لو حتى الآن مافي ولا منشور (أثناء أول دفعة)، ننشئ منشور عادي مؤقت ونشاركه
                $originalPost = Post::factory()->create();
            }
            $originalPostId = $originalPost->id;
            $type = 'text'; // المشاركات تكون نصية فقط (تعليق على المشاركة)
            $comment = $this->faker->sentence;
        }
        return [
            'user_id' => $user->id,
            'content' => !$isSharedPost && in_array($type, ['text', 'text_image', 'text_video'])
                ? $this->faker->paragraph
                : null,
            'image_url' => !$isSharedPost && in_array($type, ['image', 'text_image'])
                ? $this->faker->randomElement([
                    'https://g.aquan.website/storage/app/posts/1.png',
                    'https://g.aquan.website/storage/app/posts/2.png',
                    'https://g.aquan.website/storage/app/posts/3.png',
                ])
                : null,
            'video_url' => !$isSharedPost && in_array($type, ['video', 'text_video'])
                ? $this->faker->randomElement([
                    'https://youtu.be/AzCIKEXLteY',
                    'https://www.youtube.com/shorts/zNAi-vocJ6k?feature=share',
                    'https://www.youtube.com/shorts/2wKyd9waGP4?feature=share',
                ])
                : null,
            'original_post_id' => $originalPostId,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
