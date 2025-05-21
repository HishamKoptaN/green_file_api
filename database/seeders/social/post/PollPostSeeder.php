<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Poll;
use App\Models\Social\Post\Post;
use App\Models\User\User;

class PollPostSeeder extends Seeder
{

    public function run(): void
    {
        Poll::truncate();
        Post::where('postable_type', Poll::class)->delete();
        Post::factory()->count(3)->pollPost()->create();
    }
}
