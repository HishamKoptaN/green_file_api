<?php

namespace Database\Seeders\social\post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\PostComment;

class PostCommentsSeeder extends Seeder
{
    public function run()
    {
        PostComment::factory(100)->create();
    }
}
