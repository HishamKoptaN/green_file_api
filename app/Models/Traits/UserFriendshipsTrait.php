<?php

namespace App\Models\Traits;

use App\Models\Social\Friendship;
use App\Models\User\User;

trait UserFriendshipsTrait
{

    public function friends()
    {
        return $this->hasMany(Friendship::class, 'user_id')
            ->where('status', 'accepted')
            ->orWhere(function ($q) {
                $q->where('friend_id', $this->id)->where('status', 'accepted');
            });
    }
    // public function friends()
    // {
    //     return $this->hasMany(Friendship::class, 'user_id')->where('status', 'accepted')
    //         ->orWhere(function ($q) {
    //             $q->where('friend_id', $this->id)->where('status', 'accepted');
    //         });
    // }
    public function friendships()
    {
        return $this->hasMany(
            Friendship::class,
            'user_id',
        )->orWhere(
            'friend_id',
            $this->id,
        );
    }
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id')->where('status', 'pending');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id')->where('status', 'pending');
    }


    public function sendFriendRequest(User $user)
    {
        return $this->sentFriendRequests()->create(
            [
                'friend_id' => $user->id,
                'status' => 'pending',
            ],
        );
    }
    public function acceptFriendRequest(User $user)
    {
        return $this->receivedFriendRequests()
            ->where('user_id', $user->id)
            ->update(['status' => 'accepted']);
    }

    public function rejectFriendRequest(User $user)
    {
        return $this->receivedFriendRequests()
            ->where('user_id', $user->id)
            ->update(['status' => 'rejected']);
    }
    public function isFriendWith(User $user)
    {
        return $this->friends()
            ->where(function ($query) use ($user) {
                $query->where('friend_id', $user->id)
                    ->orWhere('user_id', $user->id);
            })->exists();
    }
}
