<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // About Page
        Page::create([
            'slug' => 'about',
            'title' => [
                'en' => 'About Me',
                'id' => 'Tentang Saya'
            ],
            'content' => [
                'en' => '<p>I am a Senior Full-Stack Architect with over 8 years of experience building scalable web applications. My passion lies in creating clean, efficient code and solving complex problems with innovative solutions.</p><p>I specialize in Laravel, Livewire, and modern frontend frameworks.</p>',
                'id' => '<p>Saya adalah Senior Full-Stack Architect dengan pengalaman lebih dari 8 tahun dalam membangun aplikasi web yang skalabel. Passion saya terletak pada pembuatan kode yang bersih, efisien, dan memecahkan masalah kompleks dengan solusi inovatif.</p><p>Saya berspesialisasi dalam Laravel, Livewire, dan framework frontend modern.</p>'
            ],
            'is_active' => true,
        ]);

        // AI Lab Page
        Page::create([
            'slug' => 'ai-lab',
            'title' => [
                'en' => 'AI Lab',
                'id' => 'Lab AI'
            ],
            'content' => [
                'en' => '<p>Welcome to my experimental playground where I explore the boundaries of Artificial Intelligence. Here, I document my journey in building Autonomous Agents, RAG Pipelines, and fine-tuning Large Language Models.</p><ul><li>Project Alpha: Sentiment Analysis Agent</li><li>Project Beta: PDF Chatbot</li></ul>',
                'id' => '<p>Selamat datang di tempat eksperimen saya di mana saya menjelajahi batas-batas Kecerdasan Buatan. Di sini, saya mendokumentasikan perjalanan saya dalam membangun Agen Otonom, Pipa RAG, dan fine-tuning Large Language Models.</p><ul><li>Proyek Alpha: Agen Analisis Sentimen</li><li>Proyek Beta: Chatbot PDF</li></ul>'
            ],
            'is_active' => true,
        ]);
    }
}
