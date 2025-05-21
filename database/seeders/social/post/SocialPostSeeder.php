<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Post;
use App\Models\Social\Post\SocialPost;
use Faker\Factory as FakerFactory;

class SocialPostSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'textOnly',
            'textWithImage',
            'textWithVideo',
            'imageOnly',
            'videoOnly',
            'pdfOnly',
            'textWithPdf',
        ];
        foreach ($types as $type) {
            Post::where('postable_type', SocialPost::class)
                ->whereHas('postable', function ($query) {
                    $query->whereNotNull('content')
                        ->whereNull('image')
                        ->whereNull('video')
                        ->whereNull('pdf');
                })->delete();
        }
        foreach ($types as $type) {
            Post::factory()
                ->count(3)
                ->state(
                    function (array $attributes) use ($type) {
                        $socialPost = SocialPost::factory()->{$type}()->create();
                        return [
                            'user_id' => FakerFactory::create()->randomElement(
                                [
                                    1,
                                    2,
                                    3,
                                    4,
                                    5,
                                    6,
                                    7,
                                    8,
                                    9,
                                    10,
                                ],
                            ),
                            'postable_type' => 'socialPost',
                            'postable_id' => $socialPost->id,
                            'publish_at' => now()->subDays(
                                rand(
                                    0,
                                    3,
                                ),
                            ),
                        ];
                    },
                )
                ->create();
        }
    }
}
