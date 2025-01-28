<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppControl;

class ControlsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'branch_name' => 'Buy Sell Status',
                'status' => 1,
                'message' => "Message 1",
            ],
            [
                'branch_name' => 'Exchange Rates',
                'status' => 1,
                'message' => "Message 2",
            ],
            [
                'branch_name' => 'Transaction Status',
                'status' => 0,
                'message' => "Message 3",
            ],
        ];

        foreach ($data as $item) {
            AppControl::create($item);
        }
    }
}
