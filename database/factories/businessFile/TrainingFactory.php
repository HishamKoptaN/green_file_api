<?php

namespace Database\Factories\BusinessFile;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User\Company;

class TrainingFactory extends Factory
{
    public function definition()
    {
        return [
            'company_id' => Company::inRandomOrder()->value('id') ?? 1,
            'image' => $this->faker->randomElement(
                [
                    'https://g.aquan.website/storage/app/training/1.png',
                    'https://g.aquan.website/storage/app/training/2.png',
                    'https://g.aquan.website/storage/app/training/3.png',
                ],
            ),
            'title' => fake()->randomElement(
                [
                    'دورة تدريبية في تطوير الويب',
                    'أساسيات التسويق الرقمي',
                    'مهارات القيادة الفعالة',
                    'تحليل البيانات باستخدام Python',
                    'بناء تطبيقات الهاتف باستخدام Flutter',
                    'إدارة المشاريع الاحترافية PMP',
                ],
            ),
           'description' => fake()->realText(75),
        ];
    }
}
