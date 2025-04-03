<?php

namespace Database\Seeders\Social;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Notification\Notification;
use App\Models\Notification\NotificationRecipient;
use Faker\Factory as Faker;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            //! إنشاء إشعار جديد
            $notification = Notification::create(
                [
                    'title' => $faker->sentence(3), // عنوان عشوائي
                    'body'  => $faker->paragraph(2), // نص عشوائي
                    'type'  => 'user',
                ],
            );
            //! تحديد المستخدمين الذين سيستلمون الإشعار
            $userIds = [1,]; //! يمكنك تغيير المستخدمين حسب الحاجة
            foreach ($userIds as $userId) {
                NotificationRecipient::create(
                    [
                        'notification_id' => $notification->id,
                        'user_id'         => $userId,
                        'is_read'         => false,
                    ],
                );
            }
        }
    }
}
