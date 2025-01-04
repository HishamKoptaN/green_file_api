<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WithdrawsSeeder extends Seeder
{
    public function run(): void
    {
        $user_id = [4, 5];
        $statuses = [
            'pending',
            'accepted',
            'rejected',
        ];
        $methods = [
            1,
            2,
            3,
            4,
            5,
        ];
        for ($i = 0; $i < 15; $i++) {
            DB::table('withdraws')->insert(
                [
                    'status' => $statuses[array_rand($statuses)],
                    'amount' => rand(100, 10000) / 100,
                    'comment' => Str::random(50),
                    'receiving_account_number' => mt_rand(100000000000000, 999999999999999),
                    'method' => $methods[array_rand($methods)],
                    'user_id' =>  $user_id[array_rand($user_id)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
