<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'title' => ['en' => 'Enterprise Web Architecture', 'id' => 'Arsitektur Web Enterprise'],
                'description' => [
                    'en' => 'Scalable microservices, robust APIs, and high-performance applications designed for millions of requests.',
                    'id' => 'Microservices yang scalable, API tangguh, dan aplikasi performa tinggi yang dirancang untuk jutaan request.'
                ],
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>',
                'category' => 'system_arch',
            ],
            [
                'title' => ['en' => 'AI Agents & RAG Pipelines', 'id' => 'Agen AI & Pipeline RAG'],
                'description' => [
                    'en' => 'Building autonomous agents that understand context, integrating advanced LLMs with Vector Databases for real business logic.',
                    'id' => 'Membangun agen otonom yang memahami konteks, mengintegrasikan LLM canggih dengan Vector Database untuk logika bisnis nyata.'
                ],
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
                'category' => 'ai_solution',
            ],
            [
                'title' => ['en' => 'System Optimization', 'id' => 'Optimasi Sistem'],
                'description' => [
                    'en' => 'Database tuning, containerization, and automated CI/CD pipelines to ensure reliability and speed.',
                    'id' => 'Tuning database, kontainerisasi, dan pipeline CI/CD otomatis untuk memastikan keandalan dan kecepatan.'
                ],
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'category' => 'web_dev',
            ],
        ];

        foreach ($services as $service) {
            Service::create(array_merge($service, [
                'slug' => Str::slug($service['title']['en']),
            ]));
        }
    }
}
