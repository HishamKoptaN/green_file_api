<?php

namespace Database\Seeders\Social;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Social\Follower;
use App\Models\User\User;

class FollowerSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        foreach ($users as $user) {
            $followedUsers = $users->where('id', '!=', $user->id)->random(rand(3, 7));
            $user->following()->syncWithoutDetaching($followedUsers->pluck('id'));
        }
    }
}
