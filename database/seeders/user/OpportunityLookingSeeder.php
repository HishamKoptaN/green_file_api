<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OpportunityLooking;

class OpportunityLookingSeeder extends Seeder
{
    public function run()
    {

        $base_url = 'https://g.aquan.website/storage/app/profile/opportunity_lookings/';
        $image = ['1.png', '2.png', '3.png'];
        $opportunityLookings = [
            [
                'status' => true,
                'first_name' => "Opportunity Looking 1",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 1",
                'phone' => "+2126000001",
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'first_name' => "Opportunity Looking 2",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],

                'address' => "address",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
            [
                'status' => true,
                'first_name' => "Opportunity Looking 2",
                'last_name' => "",
                'image' => $base_url . $image[array_rand($image)],
                'address' => "address 2",
                'phone' => "+2126000002",
                'comment' => 'comment',
            ],
        ];
        OpportunityLooking::insert($opportunityLookings);
    }
}
