<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
