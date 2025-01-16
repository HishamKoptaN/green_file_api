<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;
use App\Models\Balance;
use App\Models\RoleUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'status',
        'email',
        'online_offline',
        'firebase_uid',
        'first_name',
        'last_name',
        'account_number',
        'password',
        'image',
        'address',
        'phone',
        'phone_verified_at',
        'inactivate_end_at',
        'comment',
        'country_code',
        'verified_at',
    ];
    public function balance()
    {
        return $this->hasOne(
            Balance::class,
        );
    }

    public function account()
    {
        return $this->hasOne(
            Account::class,
            'user_id',
        );
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
    public function notifications()
    {
        return $this->belongsToMany(
            Notification::class,
            'notification_user',
            'user_id',
            'notification_id',
        );
    }
    public function getStatusAttribute(
        $value,
    ) {
        return (bool) $value;
    }


    public function accounts()
    {
        return $this->hasMany(
            Account::class,
        );
    }
    public function role()
    {
        return $this->belongsTo(
            Role::class,
        );
    }
    public function createdDate(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => $this->created_at ? $this->created_at->format('Y-m-d') : null,
        );
    }
    public function upgradedDate(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value) => $this->upgraded_at ? $this->upgraded_at->format('Y-m-d H:i') : null,
        );
    }
    public function refer()
    {
        return $this->belongsTo(User::class, 'refered_by');
    }
    public function referrals()
    {
        return $this->hasMany(User::class, 'refered_by');
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'user_id');
    }
    public function adminTransfers()
    {
        return $this->hasMany(Transfer::class, 'admin_id');
    }
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'user_tasks');
    }
    public function userPlan()
    {
        return $this->hasOne(UserPlan::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public static function findOnlineEmployee()
    {
        return DB::table('role_users')
            ->join('users', 'role_users.user_id', '=', 'users.id')
            ->where('role_users.role_id', 3)
            ->where('users.online_offline', 'online')
            ->select('users.*')
            ->first();
    }
}
