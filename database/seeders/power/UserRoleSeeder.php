<?php

namespace Database\Seeders\power;

use Illuminate\Database\Seeder;
use App\Models\User\User;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $opportunityLookingIds = [1, 2, 3];
        $companyIds = [4, 5];
        $adminIds = [9, 10];

        foreach ($users as $user) {
            if (in_array($user->id, $opportunityLookingIds)) {
                $user->assignRole(
                    'opportunity_looking',
                );
            } elseif (in_array($user->id, $companyIds)) {
                $user->assignRole(
                    'company',
                );
            } elseif (in_array($user->id, $adminIds)) {
                $user->assignRole(
                    'admin',
                );
            } else {
                $user->assignRole(
                    'opportunity_looking',
                );
            }
        }
    }
}
