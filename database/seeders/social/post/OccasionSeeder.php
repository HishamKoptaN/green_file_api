<?php

namespace Database\Seeders\Social\Post;

use Illuminate\Database\Seeder;
use App\Models\Social\Post\Occasion;
use App\Models\Social\Post\Post;
use Faker\Factory as FakerFactory;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
        // حذف البيانات القديمة
        Occasion::query()->delete();
        Post::where('postable_type', Occasion::class)->delete();
        // إنشاء 5 مناسبات
        $occasions = Occasion::factory()->count(5)->create();

        // إنشاء المنشورات العشوائية
        $occasions->each(function ($occasion) {
            Post::factory()
                ->create([
                    'user_id' => FakerFactory::create()->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                    'postable_type' => 'occasion',
                    'postable_id' => $occasion->id, // ربط المنشور بالمناسبة باستخدام postable_id
                    'publish_at' => FakerFactory::create()->dateTimeBetween('-3 days', 'now'),
                ]);
        });
    }
}
