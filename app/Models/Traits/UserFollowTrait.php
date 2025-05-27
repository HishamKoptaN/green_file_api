<?php

namespace App\Models\Traits;
use App\Models\User\User;

trait UserFollowTrait
{
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'follower_id',
            'followed_id',
        );
    }
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'followed_id',
            'follower_id',
        );
    }

    public function follow($id)
    {
        return $this->followings()->attach($id);
    }

    public function unfollow($id)
    {
        return $this->followings()->detach($id);
    }

    public function isFollowing($id)
    {
        return $this->followings()->where('followed_id', $id)->exists();
    }
    public function followings()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'follower_id',
            'followed_id',
        )->select('users.id');
    }
    public function isFollowedBy($user)
    {
        return $this->followers()
            ->where('user_id', $user->id)
            ->exists();
    }
}
