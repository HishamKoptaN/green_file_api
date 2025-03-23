<?php

namespace Database\Factories\social\statuses;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Social\Status\Status;

class StatusFactory extends Factory
{
    protected $model = Status::class;
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
            'user_id' => $this->faker->randomElement(
                [
                    1,
                    2,
                    3,
                    4,
                    5,
                ],
            ),
            'content' => in_array(
                $type,
                [
                    'text',
                    'text_image',
                    'text_video',
                ],
            ) ? $this->faker->paragraph : null,
            'image' => in_array(
                $type,
                [
                    'image',
                    'text_image',
                ],
            ) ? $this->faker->randomElement(
                [
                    env('APP_URL') . '/public/media/statuses/1.png',
                    env('APP_URL') . '/public/media/statuses/2.png',
                    env('APP_URL') . '/public//mediastatuses/3.png',
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
