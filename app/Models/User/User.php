<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Social\Status\Status;
use App\Models\Cvs\Cv;
use App\Models\Social\Post\Post;
use App\Models\Global\Report;
use App\Models\Global\Hide;
use App\Models\Traits\UserPostsTrait;
use App\Models\Traits\UserStatusTrait;
use App\Models\Traits\UserFriendshipsTrait;
use App\Models\Traits\UserOccasionTrait;
use App\Models\Traits\UserFollowTrait;
use App\Models\Traits\UserCoursesTrait;
use App\Models\Traits\UserRolesTrait;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use UserRolesTrait, UserPostsTrait, UserStatusTrait, UserOccasionTrait, UserFollowTrait, UserCoursesTrait;
    use UserFriendshipsTrait;
    use HasRoles;
    protected $fillable = [
        'status',
        'firebase_uid',
        'fcm_token',
        'userable_id',
        'userable_type',
        'online_offline',
        'verified_at',
        'created_at',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    public function contentReports()
    {
        return $this->hasMany(Report::class);
    }
    public function contentHides()
    {
        return $this->hasMany(Hide::class);
    }
    public function hiddenItems()
    {
        return $this->morphedByMany(Status::class, 'hideable', 'hides')
            ->union(
                $this->morphedByMany(Post::class, 'hideable', 'hides')
            );
    }
    public function userable()
    {
        return $this->morphTo();
    }
    public function profile()
    {
        return $this->morphTo();
    }
    public function opportunityLookings()
    {
        return $this->hasMany(OpportunityLooking::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
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
    public function cv()
    {
        return $this->hasOne(
            Cv::class,
        );
    }
}
