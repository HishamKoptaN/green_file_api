<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\SharedPost;
use App\Models\Social\Post\Post;
use App\Models\User\User;

class SharedPostSeeder extends Seeder
{
    public function run(): void
    {
        SharedPost::truncate();
        Post::where('postable_type', SharedPost::class)->delete();
        Post::factory()->count(3)->sharedPost()->create();
    }
}
