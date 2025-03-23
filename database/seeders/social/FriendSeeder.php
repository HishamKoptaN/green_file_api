<?php

namespace Database\Seeders\Social;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Social\Friendship;

class FriendSeeder extends Seeder
{
    public function run()
    {
        $users = User::pluck('id')->toArray();
        foreach ($users as $userId) {
            $friends = collect($users)
                ->reject(fn($id) => $id === $userId)
                ->shuffle()
                ->take(
                    rand(
                        3,
                        5,
                    ),
                );
            foreach ($friends as $friendId) {
                if (!$this->friendshipExists($userId, $friendId)) {
                    Friendship::create(
                        [
                            'user_id' => $userId,
                            'friend_id' => $friendId,
                            'status' => 'accepted',
                        ],
                    );
                }
            }
        }
    }

    private function friendshipExists($userId, $friendId): bool
    {
        return Friendship::where(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $userId)->where('friend_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('user_id', $friendId)->where('friend_id', $userId);
        })->exists();
    }
}
