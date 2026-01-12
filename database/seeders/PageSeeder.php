<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // About Page
        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => ['en' => 'About Me', 'id' => 'Tentang Saya'],
                'content' => ['en' => '
                    <h3 class="text-2xl font-bold mb-4">Architecting the Future with AI & Code.</h3>
                    <p class="mb-6 leading-relaxed text-lg text-slate-300">
                        I am a Senior Software Architect and AI Specialist dedicated to building high-performance, intelligent web systems. 
                        My mission is to bridge the gap between complex architectural patterns and practical AI implementation.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
                            <h4 class="text-xl font-semibold mb-2 text-indigo-400">My Mission</h4>
                            <p class="text-slate-400">To empower businesses by integrating cutting-edge AI agents into robust, scalable web infrastructures.</p>
                        </div>
                        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
                            <h4 class="text-xl font-semibold mb-2 text-cyan-400">My Vision</h4>
                            <p class="text-slate-400">A future where software not only executes commands but intelligently anticipates and solves problems.</p>
                        </div>
                    </div>
                ', 'id' => '
                    <h3 class="text-2xl font-bold mb-4">Membangun Masa Depan dengan AI & Kode.</h3>
                    <p class="mb-6 leading-relaxed text-lg text-slate-300">
                        Saya adalah Senior Software Architect dan Spesialis AI yang berdedikasi untuk membangun sistem web cerdas berkinerja tinggi.
                        Misi saya adalah menjembatani kesenjangan antara pola arsitektur yang kompleks dan implementasi AI yang praktis.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
                            <h4 class="text-xl font-semibold mb-2 text-indigo-400">Misi Saya</h4>
                            <p class="text-slate-400">Memberdayakan bisnis dengan mengintegrasikan agen AI mutakhir ke dalam infrastruktur web yang tangguh dan skalabel.</p>
                        </div>
                        <div class="bg-slate-800 p-6 rounded-xl border border-slate-700">
                            <h4 class="text-xl font-semibold mb-2 text-cyan-400">Visi Saya</h4>
                            <p class="text-slate-400">Masa depan di mana perangkat lunak tidak hanya menjalankan perintah tetapi secara cerdas mengantisipasi dan memecahkan masalah.</p>
                        </div>
                    </div>
                '],
                'is_active' => true,
            ]
        );

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
