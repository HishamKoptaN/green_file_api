<?php

namespace Database\Seeders\power;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'opportunity_looking',
            'company',
            'employee',
            'admin',
            'manager',
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(
                [
                    'name' => $role,
                    'guard_name' => 'api',
                ]
            );
        }
    }
}
