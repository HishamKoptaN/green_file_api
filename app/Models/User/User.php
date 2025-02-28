<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Social\Post\Post;
use App\Models\Course\Course;
use App\Models\Course\CourseRating;
use App\Models\Power\Role;
use App\Models\Social\Follower;
use App\Models\Social\Friendship;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'status',
        'firebase_uid',
        'userable_id',
        'userable_type',
        'online_offline',
        'verified_at',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function userable()
    {
        return $this->morphTo();
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function opportunityLookings()
    {
        return $this->hasMany(OpportunityLooking::class);
    }
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
    // ! Frindships
    public function followers()
    {
        return $this->hasMany(Friendship::class, 'friend_id')
                    ->where('status', 'accepted');
    }
    public function followings()
    {
        return $this->hasMany(Friendship::class, 'user_id')
                    ->where('status', 'accepted');
    }
    public function isFollowing($user)
    {
        return $this->followings()
                    ->where('friend_id', $user->id)
                    ->exists();
    }
    public function isFollowedBy($user)
    {
        return $this->followers()
                    ->where('user_id', $user->id)
                    ->exists();
    }
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'user_id')->where('status', 'pending');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'friend_id')->where('status', 'pending');
    }

    public function friends()
    {
        return $this->hasMany(Friendship::class, 'user_id')->where('status', 'accepted')
            ->orWhere(function ($q) {
                $q->where('friend_id', $this->id)->where('status', 'accepted');
            });
    }
    public function sendFriendRequest(User $user)
    {
        return $this->sentFriendRequests()->create([
            'friend_id' => $user->id,
            'status' => 'pending',
        ]);
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
    // ! Followers
    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }


    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    public function courseRating()
    {
        return $this->hasMany(CourseRating::class);
    }


    public function userRoles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_users',
            'user_id',
            'role_id',
        );
    }
    public function follow($userId)
    {
        if (!$this->isFollowing($userId)) {
            return $this->following()->create(['followed_id' => $userId]);
        }
        return false;
    }

    public function unfollow($userId)
    {
        return $this->following()->where('followed_id', $userId)->delete();
    }



    public function role()
    {
        return $this->belongsTo(
            Role::class,
        );
    }
    public function getCreatedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d') : null;
    }

    public function getUpgradedDateAttribute()
    {
        return $this->upgraded_at ? $this->upgraded_at->format('Y-m-d H:i') : null;
    }

    public function upgradedDate(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => $this->upgraded_at ? $this->upgraded_at->format('Y-m-d H:i') : null,
        );
    }
    public function refer()
    {
        return $this->belongsTo(
            User::class,
            'refered_by',
        );
    }
    public function referrals()
    {
        return $this->hasMany(
            User::class,
            'refered_by',
        );
    }
}
