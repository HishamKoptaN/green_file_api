<?php

namespace Database\Factories\Job;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Job\Skill;
use App\Models\Job\Specialization;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition()
    {
        $skillsBySpecialization = [
            'تطوير الويب' => ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'React', 'Vue.js', 'Node.js', 'Bootstrap', 'Tailwind CSS'],
            'تطوير تطبيقات الهاتف' => ['Flutter', 'Kotlin', 'Swift', 'React Native', 'Dart', 'Java (Android)', 'Objective-C', 'Xamarin', 'Cordova', 'Ionic'],
            'تحليل البيانات' => ['Python', 'R', 'SQL', 'Tableau', 'Power BI', 'Excel', 'Data Visualization', 'Big Data', 'Machine Learning', 'Deep Learning'],
            'التسويق الرقمي' => ['SEO', 'Google Ads', 'Facebook Ads', 'Content Marketing', 'Email Marketing', 'Affiliate Marketing', 'Social Media Marketing', 'PPC', 'E-commerce Marketing', 'Brand Strategy'],
            'التصميم الجرافيكي' => ['Photoshop', 'Illustrator', 'Figma', 'Adobe XD', 'InDesign', 'Sketch', 'After Effects', 'Cinema 4D', 'CorelDRAW', 'Canva'],
            'الأمن السيبراني' => ['Network Security', 'Ethical Hacking', 'Penetration Testing', 'Cryptography', 'Firewall Management', 'Threat Intelligence', 'Cyber Forensics', 'Malware Analysis', 'SOC Operations', 'Risk Management'],
            'إدارة المشاريع' => ['Scrum', 'Agile', 'Kanban', 'JIRA', 'Project Management', 'Lean Six Sigma', 'Stakeholder Management', 'Risk Assessment', 'Time Management', 'Budgeting'],
            'الهندسة المعمارية' => ['AutoCAD', 'Revit', '3ds Max', 'SketchUp', 'BIM', 'Lumion', 'Rhino', 'V-Ray', 'Civil 3D', 'ArchiCAD'],
            'الذكاء الاصطناعي' => ['Machine Learning', 'Deep Learning', 'TensorFlow', 'Keras', 'PyTorch', 'Computer Vision', 'Natural Language Processing', 'AI Ethics', 'Generative AI', 'Reinforcement Learning'],
            'الكتابة الإبداعية' => ['Content Writing', 'Copywriting', 'Creative Writing', 'Technical Writing', 'Screenwriting', 'Storytelling', 'Ghostwriting', 'Blogging', 'Proofreading', 'Editing']
        ];
        // اختر تخصص عشوائي
        $specialization = Specialization::inRandomOrder()->first();

        if (!$specialization) {
            $specialization = Specialization::factory()->create();
        }
        $skillName = $this->faker->unique()->randomElement($skillsBySpecialization[$specialization->name]);
        return [
            'name' => $skillName,
            'job_specialization_id' => $specialization->id,
        ];
    }
}
