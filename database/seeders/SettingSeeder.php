<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'facebook_link' => "https://facebook.com",
            'instagram_link' => "https://instagram_link.com",
            'whatsapp_link' => "https://whatsapp_link.com",
            'about_us_link' => "https://about_us_link.com",
            'app_link' => "https://app_link.com",
            'currency_symbole' => '$'
        ];

        foreach ($settings as $name => $content) {
            Setting::updateOrCreate(
                ['name' => $name],
                ['content' => $content]
            );
        }
    }
}
