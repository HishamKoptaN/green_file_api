<?php

namespace App\Services;

use App\Models\User\User;
use App\Models\User\Company;

class FollowService
{
    public static function toggle(User $authUser, User $targetUser)
    {
        if ($authUser->isFollowing($targetUser)) {
            $authUser->unfollow($targetUser);
            return 'تم إلغاء المتابعة';
        } else {
            $authUser->follow($targetUser);
            return 'تمت المتابعة';
        }
    }
    public static function getUnfollowedCompanies(
        User $authUser,
    ) {
        return User::where('userable_type', Company::class)
            ->whereDoesntHave(
                'followers',
                function ($query) use ($authUser) {
                    $query->where('follower_id', $authUser->id);
                },
            )
            ->with('userable')
            ->get();
    }
    public function followingCompanies()
    {
        return $this->followings()->where(
            'userable_type',
            Company::class,
        );
    }
}
