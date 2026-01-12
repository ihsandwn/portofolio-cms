<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first() ?? \App\Models\User::factory()->create();

        // 1. Text Only Post
        \App\Models\BlogPost::create([
            'title' => [
                'en' => 'The Future of Laravel',
                'id' => 'Masa Depan Laravel',
            ],
            'slug' => 'future-of-laravel',
            'excerpt' => [
                'en' => 'Exploring the streamlined structure of Laravel 11, Reverb, and the cloud-native future of the framework.',
                'id' => 'Menjelajahi struktur ramping Laravel 11, Reverb, dan masa depan cloud-native dari framework ini.',
            ],
            'content_blocks' => [
                'en' => [
                    ['type' => 'text', 'data' => '<h2>A New Era of Minimalism</h2><p>Laravel 11 introduced a streamlined application structure, reducing the amount of boilerplate code for new applications. This philosophy of "less is more" allows developers to focus on building features rather than managing configuration.</p>'],
                    ['type' => 'text', 'data' => '<h2>First-Party WebSocket Server: Reverb</h2><p>Real-time applications have never been easier. With Laravel Reverb, you get a blazingly fast, scalable WebSocket server directly integrated into the ecosystem, optimized for high-traffic applications.</p>'],
                    ['type' => 'text', 'data' => '<h2>Performance at Scale</h2><p>With native support for FrankenPHP and continued improvements to Laravel Octane, the framework is pushing the boundaries of PHP performance, competing with Go and Node.js for high-concurrency workloads.</p>'],
                    ['type' => 'text', 'data' => '<h3>What lies ahead?</h3><p>The future of Laravel is focused on cloud-native development, seamless AI integration, and developer experience that remains second to none.</p>'],
                ],
                'id' => [
                    ['type' => 'text', 'data' => '<h2>Era Baru Minimalisme</h2><p>Laravel 11 memperkenalkan struktur aplikasi yang ramping, mengurangi jumlah kode boilerplate untuk aplikasi baru. Filosofi "less is more" ini memungkinkan pengembang untuk fokus membangun fitur daripada mengelola konfigurasi.</p>'],
                    ['type' => 'text', 'data' => '<h2>Server WebSocket Pihak Pertama: Reverb</h2><p>Aplikasi real-time belum pernah semudah ini. Dengan Laravel Reverb, Anda mendapatkan server WebSocket yang sangat cepat dan scalable yang terintegrasi langsung ke dalam ekosistem, dioptimalkan untuk aplikasi lalu lintas tinggi.</p>'],
                    ['type' => 'text', 'data' => '<h2>Performa Skala Besar</h2><p>Dengan dukungan asli untuk FrankenPHP dan peningkatan berkelanjutan pada Laravel Octane, framework ini mendorong batas performa PHP, bersaing dengan Go dan Node.js menghandle beban kerja konkurensi tinggi.</p>'],
                    ['type' => 'text', 'data' => '<h3>Apa yang ada di depan?</h3><p>Masa depan Laravel difokuskan pada pengembangan cloud-native, integrasi AI yang mulus, dan pengalaman pengembang yang tetap tidak ada duanya.</p>'],
                ],
            ],
            'seo_meta' => ['title' => 'The Future of Laravel: 11 and Beyond', 'description' => 'Deep dive into Laravel 11 features: Reverb, FrankenPHP, and the simplified directory structure.'],
            'author_id' => $user->id,
            'published_at' => now(),
            'category_id' => 1,
        ]);

        // 2. Mixed Content (Gallery + Video)
        \App\Models\BlogPost::create([
            'title' => [
                'en' => 'Visual Guide to AI',
                'id' => 'Panduan Visual AI',
            ],
            'slug' => 'visual-guide-ai',
            'excerpt' => [
                'en' => 'A visual tour of AI concepts and how they are changing the landscape of technology.',
                'id' => 'Tur visual konsep AI dan bagaimana mereka mengubah lanskap teknologi.',
            ],
            'content_blocks' => [
                'en' => [
                    ['type' => 'text', 'data' => '<h2>Introduction</h2><p>Artificial Intelligence is not just buzzwords; it represents a fundamental shift in computing. From generative art to predictive analytics, AI is everywhere.</p>'],
                    ['type' => 'gallery', 'images' => ['gallery1.jpg', 'gallery2.jpg']], // Dummy images
                    ['type' => 'text', 'data' => '<p>Below is a video demonstrating the latest advancements in neural rendering.</p>'],
                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ],
                'id' => [
                    ['type' => 'text', 'data' => '<h2>Pengantar</h2><p>Kecerdasan Buatan bukan hanya sekedar kata-kata populer; ini mewakili pergeseran mendasar dalam komputasi. Dari seni generatif hingga analitik prediktif, AI ada di mana-mana.</p>'],
                    ['type' => 'gallery', 'images' => ['gallery1.jpg', 'gallery2.jpg']], // Dummy images
                    ['type' => 'text', 'data' => '<p>Di bawah ini adalah video yang mendemonstrasikan kemajuan terbaru dalam rendering saraf.</p>'],
                    ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                ],
            ],
             'seo_meta' => ['title' => 'Visual Guide to AI', 'description' => 'AI Gallery and Video'],
            'author_id' => $user->id,
            'published_at' => now()->subDays(2),
             'category_id' => 1,
        ]);
        
        // 3. Mixed Media (Image + PDF + Text)
        \App\Models\BlogPost::create([
            'title' => [
                'en' => 'Comprehensive Web Security Guide',
                'id' => 'Panduan Komprehensif Keamanan Web',
            ],
            'slug' => 'web-security-guide-2026',
            'excerpt' => [
                'en' => 'A complete roadmap for securing modern web applications, including a downloadable checklist.',
                'id' => 'Peta jalan lengkap untuk mengamankan aplikasi web modern, termasuk daftar periksa yang dapat diunduh.',
            ],
            'content_blocks' => [
                'en' => [
                    ['type' => 'text', 'data' => '<h2>Why Security Matters?</h2><p>In 2026, web security is more critical than ever.</p>'],
                    ['type' => 'gallery', 'images' => ['security-diagram.jpg']], // Single image acting as a diagram
                    ['type' => 'text', 'data' => '<h3>Download the Checklist</h3><p>Keep track of your security audit with our free PDF.</p>'],
                    ['type' => 'pdf', 'file' => 'security-checklist.pdf', 'title' => 'Web Security Checklist 2026'],
                ],
                'id' => [
                    ['type' => 'text', 'data' => '<h2>Mengapa Keamanan Penting?</h2><p>Di tahun 2026, keamanan web lebih penting dari sebelumnya.</p>'],
                    ['type' => 'gallery', 'images' => ['security-diagram.jpg']], // Single image acting as a diagram
                    ['type' => 'text', 'data' => '<h3>Unduh Daftar Periksa</h3><p>Lacak audit keamanan Anda dengan PDF gratis kami.</p>'],
                    ['type' => 'pdf', 'file' => 'security-checklist.pdf', 'title' => 'Daftar Periksa Keamanan Web 2026'],
                ],
            ],
             'seo_meta' => ['title' => 'Web Security Guide', 'description' => 'Security Guide & Checklist'],
            'author_id' => $user->id,
            'published_at' => now()->subDay(),
             'category_id' => 2,
        ]);
        
        // Generate 7 more random generic posts
        // \App\Models\BlogPost::factory()->count(7)->create([
        //     'author_id' => $user->id
        // ]);
    }
}
