<?php

namespace Database\Seeders\social\post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Post;

class PostSeeder extends Seeder
{
    public function run()
    {
        Post::factory(15)->create();
    }
}
