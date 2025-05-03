<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            "تصميم جرافيك",
            "تصميم واجهات مواقع",
            "مونتاج فيديو",
            "موشن جرافيك",
            "تعليق صوتي",
            "ترجمة",
            "كتابة محتوى متخصص",
            "هندسة معمارية",
            "هندسة انشائية",
            "هندسة ميكانيكية",
            "دراسة جدوى",
            "ادخال بيانات",
            "خطط تسويقية",
            "حملات اعلانية",
            "إدارة حسابات التواصل الاجتماعي",
            "إدارة مواقع ومتاجر",
            "خدمات قانونية",
            "خدمات مالية",
            "خدمات محاسبية",
            "برمجة مواقع",
            "برمجة متاجر",
            "برمجة تطبيقات",
            "استشارات نفسية",
            "استشارات صحية",
            "تطوير الذات",
        ];

        foreach ($categories as $category) {
            DB::table('specializations')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
