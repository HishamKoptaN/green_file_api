<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(
            [
                PermissionsSeeder::class,
                RolesSeeder::class,
                PlanSeeder::class,
                UserSeeder::class,
                UserPlanSeeder::class,
                BalancesSeeder::class,
                RoleUserSeeder::class,
                CurrencySeeder::class,
                AccountsSeeder::class,
                TransferSeeder::class,
                SettingSeeder::class,
                ControlsSeeder::class,
                TasksSeeder::class,
                TaskProofsSeeder::class,
                RatesSeeder::class,
                PlanInvoicesSeeder::class,
                WithdrawsSeeder::class,
                DepositsSeeder::class,
                NotificationsSeeder::class,
            ],
        );
    }
}
