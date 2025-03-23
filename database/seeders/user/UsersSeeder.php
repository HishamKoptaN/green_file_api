<?php

namespace Database\Seeders\User;

use Illuminate\Database\Seeder;
use App\Models\User\OpportunityLooking;
use App\Models\User\Company;
use App\Models\User\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [

                'id' => 1,
                'userable_id' => 1,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'SGCzShj8BKdAv4Ba7zbPzmE9bgr1',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 2,
                'userable_id' => 2,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'vg3u9J75ekPpC6q67OcOORdp1rn1',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 3,
                'userable_id' => 3,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts7676ZbUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 4,
                'userable_id' => 4,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts7676sdsdZbUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 5,
                'userable_id' => 5,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts7676ZsdsdbUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],


            [
                'id' => 6,
                'userable_id' => 6,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts7676ZsdsadbUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],


            [
                'id' => 7,
                'userable_id' => 7,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts7676Zsdsd12bUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],


            [
                'id' => 8,
                'userable_id' => 8,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts767dsd6ZbUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],


            [
                'id' => 9,
                'userable_id' => 9,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts76sdsd76ZbUYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],


            [
                'id' => 10,
                'userable_id' => 10,
                'userable_type' => OpportunityLooking::class,
                'status' => true,
                'firebase_uid' => 'kC6Ts7676ZbUssdYpKHy7PvqY96huD2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            //! Company
            [
                'id' => 11,
                'userable_id' => 4,
                'userable_type' => Company::class,
                'status' => true,
                'firebase_uid' => 'MGReFr2p4DZcR9oW51d4eysIl5O2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 12,
                'userable_id' => 5,
                'userable_type' => Company::class,
                'status' => true,
                'firebase_uid' => 'oskFMJPzWIV7S9Nfxae15M5bqme2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 13,
                'userable_id' => 5,
                'userable_type' => Company::class,
                'status' => true,
                'firebase_uid' => 'oskFMJPzWIV7S19Nfxae15M5bqme2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 14,
                'userable_id' => 5,
                'userable_type' => Company::class,
                'status' => true,
                'firebase_uid' => 'os2kFMJPzWIV7S9Nfxae15M5bqme2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
            [
                'id' => 15,
                'userable_id' => 5,
                'userable_type' => Company::class,
                'status' => true,
                'firebase_uid' => 'oskFMJPzWIV7S9N1fxae15M5bqme2',
                'online_offline' => 'online',
                'inactivate_end_at' => null,
            ],
        ];
        foreach ($users as $userData) {
            User::updateOrInsert(
                ['id' => $userData['id']],
                $userData
            );

        }

    }
}
