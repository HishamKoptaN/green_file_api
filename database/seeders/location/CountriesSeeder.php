<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('countries')->insert(
            [
                ['code' => 'EG'],
                ['code' => 'SA'],
                ['code' => 'QR'],
                ['code' => 'AE'],
                ['code' => 'OM'],
                ['code' => 'KW'],
                ['code' => 'BH'],
                ['code' => 'IQ'],
                ['code' => 'JO'],
                ['code' => 'LB'],
                ['code' => 'SY'],
                ['code' => 'YE'],
                ['code' => 'SD'],
                ['code' => 'LY'],
                ['code' => 'MA'],
                ['code' => 'DZ'],
                ['code' => 'TN'],
                ['code' => 'PS'],
            ],
        );
    }
}
