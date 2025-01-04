<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepositsSeeder extends Seeder
{
    public function run(): void
    {
        $image =  ['invoice1.png', 'invoice2.png', 'invoice3.png'];
        $user_id = [4, 5];
        $statuses = ['pending', 'accepted', 'rejected'];
        $methods = [1, 2, 3, 4, 5];
        for ($i = 0; $i < 15; $i++) {
            DB::table('deposits')->insert(
                [
                    'status' => $statuses[array_rand($statuses)],
                    'image' => "https://api.aquan.website/public/storage/deposits/" . $image[array_rand($image)],
                    'amount' => rand(100, 10000) / 100,
                    'comment' => Str::random(50),
                    'method' => $methods[array_rand($methods)],
                    'user_id' =>  $user_id[array_rand($user_id)],
                    'employee_id' =>  '3',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
