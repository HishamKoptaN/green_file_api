<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Notification;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $notifications = [];
        for ($i = 0; $i < 10; $i++) {

            $notifications[] = [
                'public' => true,
                'message' => 'This is notification number ' . ($i + 1),
            ];
        }
        DB::table('notifications')->insert($notifications);
    }
}
