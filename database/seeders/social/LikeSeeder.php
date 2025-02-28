<?php

namespace Database\Seeders\Social;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Social\Post\Like;

class LikeSeeder extends Seeder
{

    public function run()
    {
        Like::factory(100)->create();
    }
}
