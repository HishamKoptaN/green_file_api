<?php

namespace Database\Seeders\Chat;
use App\Models\Chats\Chat;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(15)->get();
        // لإنشاء محادثات بدون تكرار
        foreach ($users as $index => $user1) {
            for ($j = $index + 1; $j < count($users); $j++) {
                $user2 = $users[$j];
                Chat::create([
                    'user_1_id' => $user1->id,
                    'user_2_id' => $user2->id,
                ]);
            }
        }

        $totalChats = Chat::count();
        $this->command->info("✅ Created $totalChats chat rooms between 15 users.");
    }
}
