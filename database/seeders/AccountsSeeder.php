<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Account;

class AccountsSeeder extends Seeder
{
    public function run(): void
    {
        $users = ['1', '2', '3', '4', '5'];
        $banks = ['1', '2', '3', '4', '5'];
        foreach ($users as $user) {
            foreach ($banks as $bank) {
                Account::create(
                    [
                        'user_id' => $user,
                        'currency_id' => $bank,
                        'account_number' => '24' . rand(10000, 99999),
                        'comment' => 'Comment for user ' . $user . ' at bank ' . $bank,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                );
            }
        }
    }
}
