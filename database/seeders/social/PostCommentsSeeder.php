<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostComment;

class PostCommentsSeeder extends Seeder
{
    public function run()
    {
        PostComment::factory(100)->create();
    }
}
