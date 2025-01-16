<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleUserSeeder extends Seeder
{
    public function run()
    {
        $usersWithRoles = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
        ];

        // foreach ($usersWithRoles as $userId => $roleId) {
        //     $user = User::find(
        //         $userId,
        //     );
        //     $role = Role::find(
        //         $roleId,
        //     );
        //     if ($user && $role) {
        //         $user->roles()->syncWithoutDetaching(
        //             [
        //                 $role->id,
        //             ],
        //         );
        //     }
        // }
    }
}
