<?php

namespace Database\Seeders\Job;

use Illuminate\Database\Seeder;
use App\Models\Job\Skill;
use App\Models\Job\JobSpecialization;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = JobSpecialization::all();
        $skillsPerSpecialization = 10; // عدد المهارات لكل تخصص

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

        $specializationIndex = 0;
        foreach ($skillsBySpecialization as $specializationName => $skills) {
            $specialization = $specializations->skip($specializationIndex)->first();

            if (!$specialization) {
                continue;
            }

            foreach ($skills as $skill) {
                Skill::create([
                    'name' => $skill,
                    'job_specialization_id' => $specialization->id,
                ]);
            }

            $specializationIndex++;
        }
    }
}
