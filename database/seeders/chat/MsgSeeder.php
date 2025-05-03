<?php

namespace Database\Seeders\Chat;

use App\Models\Chats\Msg;
use App\Models\Chats\Chat;
use Illuminate\Database\Seeder;

class MsgSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $chats = Chat::all();

        foreach ($chats as $chat) {
            // إنشاء عدد عشوائي من الرسائل لكل محادثة
            $messagesCount = rand(30, 50);
            for ($i = 0; $i < $messagesCount; $i++) {
                // اختيار مرسل الرسالة من بين طرفي المحادثة
                $senderId = rand(0, 1) ? $chat->user_1_id : $chat->user_2_id;
                Msg::create(
                    [
                        'chat_id' => $chat->id,
                        'user_id' => $senderId,
                        'msg' => $faker->sentence(),
                        'created_at' => now()->subMinutes(rand(1, 1000)),
                    ],
                );
            }
        }

        $this->command->info("✅ تم إنشاء رسائل عشوائية للمحادثات: " . Msg::count() . " رسالة.");
    }
}
