<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlanInvoicesSeeder extends Seeder
{

    public function run(): void
    {
        $image =  ['invoice1.png', 'invoice2.png', 'invoice3.png'];
        $user_id = [4, 5];
        $plan_id = [1, 2, 3];
        $statuses = ['pending', 'accepted', 'rejected'];
        $methods = [1, 2, 3, 4, 5,];
        for ($i = 0; $i < 15; $i++) {
            DB::table('plan_invoices')->insert(
                [
                    'status' =>  $statuses[array_rand($statuses)],
                    'image' => "https://api.aquan.website/public/storage/plan-proofs/" . $image[array_rand($image)],
                    'amount' =>  rand(100, 10000) / 100,
                    'comment' => Str::random(50),
                    'currency_id' =>  $methods[array_rand($methods)],
                    'user_id' => $user_id[array_rand($user_id)],
                    'plan_id' => $plan_id[array_rand($plan_id)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
