<?php

namespace Database\Seeders\power;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'manage-users',
            'manage-support',
            'manage-notifications',
            'manage-roles',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                [
                    'name' => $permission,
                ],
            );
        }
    }
}
