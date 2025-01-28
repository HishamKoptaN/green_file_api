<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'manage-users',
            'manage-rates',
            'manage-support',
            'manage-app-controller',
            'manage-notifications',
            'manage-deposits',
            'manage-withdraws',
            'manage-transfers',
            'manage-tasks',
            'manage-task-proof',
            'manage-plans',
            'manage-proof-plans',
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
