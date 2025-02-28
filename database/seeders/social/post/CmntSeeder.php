<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Comment;

class CmntSeeder extends Seeder
{
    public function run()
    {
        Comment::factory(900)->create();
    }
}
