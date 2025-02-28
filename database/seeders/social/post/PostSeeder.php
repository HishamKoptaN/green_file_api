<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Post;
class PostSeeder extends Seeder
{
    public function run()
    {
        Post::factory(50)->create();
    }
}
