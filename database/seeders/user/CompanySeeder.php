<?php

namespace Database\Seeders\user;

use Illuminate\Database\Seeder;
use App\Models\User\Company;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $base_url =  env('APP_URL') .'/public/media/profile/companies/';
        $image = ['1.png', '2.png', '3.png'];
        $companies = [
            [
                'id' => 11,
                'name' => 'Company1',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
            [
                'id' => 12,
                'name' => 'Company2',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
            [
                'id' => 13,
                'name' => 'Company3',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
            [
                'id' => 14,
                'name' => 'Company4',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
            [
                'id' => 15,
                'name' => 'Company5',
                'phone' => '1234567',
                'country_id' => 1,
                'city_id' => 1,
                'image' => $base_url . $image[array_rand($image)],
            ],
        ];
        foreach ($companies as $company) {
            Company::updateOrInsert(
                ['id' => $company['id']],
                $company
            );
        }
    }
}
