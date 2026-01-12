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
            'title' => 'The Future of Laravel',
            'slug' => 'future-of-laravel',
            'excerpt' => 'Exploring the new features in Laravel 12.',
            'content_blocks' => [
                ['type' => 'text', 'data' => '<p>Laravel 12 brings amazing changes...</p>'],
            ],
            'seo_meta' => ['title' => 'Future of Laravel', 'description' => 'Laravel 12 features'],
            'author_id' => $user->id,
            'published_at' => now(),
            'category_id' => 1,
        ]);

        // 2. Mixed Content (Gallery + Video)
        \App\Models\BlogPost::create([
            'title' => 'Visual Guide to AI',
            'slug' => 'visual-guide-ai',
            'excerpt' => 'A visual tour of AI concepts.',
            'content_blocks' => [
                ['type' => 'text', 'data' => '<h2>Introduction</h2><p>AI is changing everything.</p>'],
                ['type' => 'gallery', 'images' => ['gallery1.jpg', 'gallery2.jpg']], // Dummy images
                ['type' => 'video', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ],
             'seo_meta' => ['title' => 'Visual Guide to AI', 'description' => 'AI Gallery and Video'],
            'author_id' => $user->id,
            'published_at' => now()->subDays(2),
             'category_id' => 1,
        ]);

         // 3. Ebook Post
        \App\Models\BlogPost::create([
            'title' => 'Download Our Whitepaper',
            'slug' => 'download-whitepaper',
            'excerpt' => 'Read our latest research.',
            'content_blocks' => [
                ['type' => 'text', 'data' => '<p>Click below to read.</p>'],
                ['type' => 'pdf', 'file' => 'dummy.pdf', 'title' => 'Research 2026'],
            ],
             'seo_meta' => ['title' => 'Whitepaper', 'description' => 'Research PDF'],
            'author_id' => $user->id,
            'published_at' => now()->subWeek(),
             'category_id' => 2,
        ]);
        
        // Generate 7 more random generic posts
        // \App\Models\BlogPost::factory()->count(7)->create([
        //     'author_id' => $user->id
        // ]);
    }
}
