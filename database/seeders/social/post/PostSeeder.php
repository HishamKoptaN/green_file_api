<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Post;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Event Posts
        Post::factory()->count(15)->eventPost()->create();

        // Poll Posts
        Post::factory()->count(15)->pollPost()->create();

        // Shared Posts
        // Post::factory()->count(15)->sharedPost()->create();
    }
}
