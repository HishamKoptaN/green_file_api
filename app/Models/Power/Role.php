<?php

namespace App\Models\Power;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User\User;

class Role extends  SpatieRole
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
    ];
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_users',
            'role_id',
            'user_id',
        );
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }
}
