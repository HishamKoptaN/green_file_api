<?php

namespace Database\Factories\Social\Post;

use App\Models\Social\Post\SocialPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialPostFactory extends Factory
{
    protected $model = SocialPost::class;

    public function definition(): array
    {
        return [
            'content' => null,
            'image' => null,
            'video' => null,
            'pdf' => null,
        ];
    }

    public function textOnly()
    {
        return $this->state(
            fn() => [
                'content' => $this->faker->paragraph,
            ],
        );
    }

    public function textWithImage()
    {
        return $this->state(
            fn() => [
                'content' => $this->faker->paragraph,
                'image' => 'https://res.cloudinary.com/dqzu6ln2h/image/upload/v1741171126/samples/cup-on-a-table.jpg',
            ],
        );
    }

    public function textWithVideo()
    {
        return $this->state(
            fn() => [
                'content' => $this->faker->paragraph,
                'video' => 'https://res.cloudinary.com/dqzu6ln2h/video/upload/v1746488466/videos/1/video.mp4',
            ],
        );
    }

    public function imageOnly()
    {
        return $this->state(
            fn() => [
                'image' => 'https://res.cloudinary.com/dqzu6ln2h/image/upload/v1741171126/samples/cup-on-a-table.jpg',
            ],
        );
    }

    public function videoOnly()
    {
        return $this->state(
            fn() => [
                'video' => 'https://res.cloudinary.com/dqzu6ln2h/video/upload/v1746488466/videos/1/video.mp4',
            ],
        );
    }

    public function pdfOnly()
    {
        return $this->state(
            fn() => [
                'pdf' => 'https://console.cloudinary.com/console/c-692b5b266e3afff44f23e4316af62a/media_library/asset/836ec7f60bbf3dc967b7dfa3ecc94034/manage',
            ],
        );
    }

    public function textWithPdf()
    {
        return $this->state(
            fn() => [
                'content' => $this->faker->paragraph,
                'pdf' => 'https://console.cloudinary.com/console/c-692b5b266e3afff44f23e4316af62a/media_library/asset/836ec7f60bbf3dc967b7dfa3ecc94034/manage',
            ],
        );
    }
}
