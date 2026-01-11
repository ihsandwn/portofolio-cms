<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'hero_badge',
                'value' => [
                    'en' => 'Available for AI & Architecture Consulting',
                    'id' => 'Tersedia untuk Konsultasi AI & Arsitektur',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
            [
                'key' => 'hero_title_prefix',
                'value' => [
                    'en' => 'Building the',
                    'id' => 'Membangun',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
            [
                'key' => 'hero_title_highlight',
                'value' => [
                    'en' => 'Future',
                    'id' => 'Masa Depan',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
            [
                'key' => 'hero_title_suffix',
                'value' => [
                    'en' => 'of Intelligent Web Systems.',
                    'id' => 'Sistem Web Cerdas.',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
            [
                'key' => 'hero_description',
                'value' => [
                    'en' => 'Senior Full-Stack Architect with 9+ years of experience, now engineering autonomous agents and scalable AI solutions for the enterprise.',
                    'id' => 'Arsitek Full-Stack Senior dengan pengalaman 9+ tahun, kini merancang agen otonom dan solusi AI yang scalable untuk perusahaan.',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
            [
                'key' => 'hero_cta_projects',
                'value' => [
                    'en' => 'View Projects',
                    'id' => 'Lihat Proyek',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
            [
                'key' => 'hero_cta_cv',
                'value' => [
                    'en' => 'Download CV',
                    'id' => 'Unduh CV',
                ],
                'type' => 'text',
                'group' => 'home',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
