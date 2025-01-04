<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'manager',
            'admin',
            'plans-admin',
            'employee',
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(
                [
                    'name' => $role,
                ],
            );
        }

        $rolesWithPermissions = [
            'manager' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
            'admin' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,],
            'plans-admin' => [1, 2, 3, 4, 5, 6, 7,],
            'employee' => [1, 2, 3, 4, 5,],
        ];
        foreach ($rolesWithPermissions as $roleName => $permissionIds) {
            $role = Role::where(
                'name',
                $roleName,
            )->first();
            if ($role) {
                $permissions = Permission::whereIn(
                    'id',
                    $permissionIds,
                )->get();
                $role->syncPermissions(
                    $permissions,
                );
            }
        }
    }
}
