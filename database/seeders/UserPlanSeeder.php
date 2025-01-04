<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use App\Models\UserPlan;
use Carbon\Carbon;

class UserPlanSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() < 5 || Plan::count() < 1) {
            $this->command->error(
                'يجب أن يكون لديك على الأقل 5 مستخدمين وخطة واحدة.',

            );
            return;
        }
        $users = User::take(5)->get();
        $plan = Plan::first();
        foreach ($users as $user) {
            UserPlan::create(
                [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'upgraded_at' => Carbon::now(),
                ],
            );
        }
        $this->command->info(
            'تم إضافة خطط لـ 5 مستخدمين بنجاح.',
        );
    }
}
