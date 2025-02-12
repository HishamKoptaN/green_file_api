<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('orders')->insert([
            [
                'user_id' => 1,
                'amount' => 100.50,
                'order_date' => Carbon::createFromDate(2023, 12, 15),
            ],
            [
                'user_id' => 2,
                'amount' => 250.00,
                'order_date' => Carbon::createFromDate(2024, 1, 10),
            ],
            [
                'user_id' => 3,
                'amount' => 75.75,
                'order_date' => Carbon::createFromDate(2023, 5, 23),
            ],
            // يمكنك إضافة المزيد من البيانات هنا
        ]);
    }
}
