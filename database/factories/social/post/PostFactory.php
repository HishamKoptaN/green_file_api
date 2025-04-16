<?php

namespace Database\Factories\Social\Post;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\User;
use App\Models\Social\Post\Post;
use App\Models\Social\Post\Poll;
use App\Models\Social\Post\Event;
use App\Models\Social\Post\SharedPost;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $user = User::inRandomOrder()->first();
        $createdAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        return [
            'user_id' => $user->id,
            'postable_type' => null,
            'postable_id' => null,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    public function eventPost()
    {
        return $this->state(function (array $attributes) {
            $event = Event::create(
                [
                    'title' => $this->faker->sentence,
                    'description' => $this->faker->paragraph,
                    'link' => $this->faker->url,
                    'start_at' => $this->faker->dateTimeBetween('now', '+1 week'),
                    'end_at' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
                    'image' => $this->faker->randomElement(
                        [
                            env('APP_URL') . '/public/media/events/1.jpg',
                            env('APP_URL') . '/public/media/events/2.jpg',
                        ],
                    ),
                ],
            );

            return [
                'postable_type' => get_class($event),
                'postable_id' => $event->id,
            ];
        });
    }

    public function pollPost()
    {
        return $this->state(function (array $attributes) {
            $poll = Poll::create([]);
            $poll->options()->createMany([
                ['option' => $this->faker->word],
                ['option' => $this->faker->word],
                ['option' => $this->faker->word],
            ]);
            return [
                'postable_type' => get_class($poll),
                'postable_id' => $poll->id,
            ];
        });
    }

    // public function sharedPost()
    // {
    //     return $this->state(
    //         function (array $attributes) {
    //             $user = User::inRandomOrder()->first();

    //             $originalPost = Post::where('user_id', '!=', $user->id)->inRandomOrder()->first()
    //                 ?? SharedPost::factory()->create();

    //             return [
    //                 'original_post_id' => $originalPost->id,
    //                 'content' => null,
    //                 'image' => null,
    //                 'video' => null,
    //             ];
    //         },
    //     );
    // }
}
