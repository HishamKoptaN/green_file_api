<?php

namespace Database\Factories\Job;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job\Specialization;


class SpecializationFactory extends Factory
{
    protected $model = Specialization::class;

    public function definition()
    {
        $specializations = [
            'تطوير الويب',
            'تطوير تطبيقات الهاتف',
            'تحليل البيانات',
            'التسويق الرقمي',
            'التصميم الجرافيكي',
            'الأمن السيبراني',
            'إدارة المشاريع',
            'الهندسة المعمارية',
            'الذكاء الاصطناعي',
            'الكتابة الإبداعية'
        ];
        return [
            'name' => $this->faker->unique()->randomElement($specializations),
        ];
    }
}
