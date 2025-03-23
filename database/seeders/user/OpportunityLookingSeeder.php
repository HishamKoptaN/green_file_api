<?php

namespace Database\Seeders\user;

use Illuminate\Database\Seeder;
use App\Models\User\OpportunityLooking;

class OpportunityLookingSeeder extends Seeder
{
    public function run()
    {
        $base_url =  env('APP_URL') .'/public/media/profile/opportunity_lookings/';
        $image = [
            '1.png',
            '2.png',
            '3.png',
        ];
        $opportunityLookings = [
            [
                'id' => 1,
                'first_name' => "freelancer1",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 1",
                'phone' => "+2126000001",
                'comment' => 'comment',
            ],
            [
                'id' => 2,
                'first_name' => "freelancer2",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 3,
                'first_name' => "freelancer3",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 4,
                'first_name' => "freelancer4",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 5,
                'first_name' => "freelancer5",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 6,
                'first_name' => "freelancer6",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 7,
                'first_name' => "freelancer7",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 8,
                'first_name' => "freelancer8",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 9,
                'first_name' => "freelancer9",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'id' => 10,
                'first_name' => "freelancer10",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
        ];
        foreach ($opportunityLookings as $opportunityLooking) {
            OpportunityLooking::updateOrInsert(
                ['id' => $opportunityLooking['id'],
            ],
                $opportunityLooking
            );
        }

    }
}
