<?php

namespace App\Models\Traits;
use App\Models\Power\Role;
trait UserRolesTrait
{
    public function userRoles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_users',
            'user_id',
            'role_id',
        );
    }
    public function role()
    {
        return $this->belongsTo(
            Role::class,
        );
    }
}
