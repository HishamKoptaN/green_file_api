<?php

namespace Database\Seeders\user;

use Illuminate\Database\Seeder;
use App\Models\User\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $base_url = 'https://g.aquan.website/storage/app/profile/companies/';
        $image = ['1.png', '2.png', '3.png'];
        $companies = [
            [
                'id' => 4,
                'name' => 'Company1',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
            [
                'id' => 5,
                'name' => 'Company2',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
        ];

        Company::insert($companies);
    }
}
