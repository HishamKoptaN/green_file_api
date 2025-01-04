<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Balance;

class BalancesSeeder extends Seeder
{
    public function run()
    {
        for ($userId = 1; $userId <= 5; $userId++) {
            $user = User::find(
                $userId,
            );
            if ($user) {
                $balance = Balance::firstOrCreate(
                    ['user_id' => $userId],
                    ['available_balance' => 0, 'suspended_balance' => 0]
                );
                $balance->available_balance += 1000;
                $balance->suspended_balance += 500;
                $balance->save();
            }
        }
    }
}
