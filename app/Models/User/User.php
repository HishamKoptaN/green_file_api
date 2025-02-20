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

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'status',
        'online_offline',
        'firebase_uid',
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
    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
    public function courseRating()
    {
        return $this->hasMany(CourseRating::class);
    }
    public function following()
    {
        return $this->morphToMany(self::class, 'followable', 'followers', 'user_id', 'followable_id')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    public function followers()
    {
        return $this->morphToMany(self::class, 'followable', 'followers', 'followable_id', 'user_id')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    // public function userWorkExperiences()
    // {
    //     return $this->hasMany(WorkExperience::class);
    // }
    // public function balance()
    // {
    //     return $this->hasOne(
    //         Balance::class,
    //     );
    // }
    // public function account()
    // {
    //     return $this->hasOne(
    //         Account::class,
    //         'user_id',
    //     );
    // }
    public function userRoles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_users',
            'user_id',
            'role_id',
        );
    }
    // public function sentFriendRequests()
    // {
    //     return $this->hasMany(Friend::class, 'user_id')->where('status', 'pending');
    // }

    // public function receivedFriendRequests()
    // {
    //     return $this->hasMany(Friend::class, 'friend_id')->where('status', 'pending');
    // }

    // public function friends()
    // {
    //     return $this->hasMany(Friend::class, 'user_id')->where('status', 'accepted');
    // }

    // public function notifications()
    // {
    //     return $this->belongsToMany(
    //         Notification::class,
    //         'notification_user',
    //         'user_id',
    //         'notification_id',
    //     );
    // }
    // public function accounts()
    // {
    //     return $this->hasMany(
    //         Account::class,
    //     );
    // }
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
        return $this->belongsTo(User::class, 'refered_by',
    );
    }
    public function referrals()
    {
        return $this->hasMany(User::class, 'refered_by',
    );
    }
    // public function userPlan()
    // {
    //     return $this->hasOne(UserPlan::class);
    // }
}
