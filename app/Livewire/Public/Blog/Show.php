<?php

namespace App\Livewire\Public\Blog;

use App\Models\BlogPost;
use Livewire\Component;

class Show extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        
        // Handle SEO
        // Manual SEO meta injection logic (simplified if no package)
    }

    public function render()
    {
        $related = BlogPost::published()
            ->where('id', '!=', $this->post->id)
            ->where('category_id', $this->post->category_id)
            ->limit(3)
            ->get();

        return view('livewire.public.blog.show', [
            'related_posts' => $related
        ])->layout('components.layouts.app', [  // Assuming generic app layout 
            'title' => $this->post->seo_meta['title'] ?? $this->post->title,
            'description' => $this->post->seo_meta['description'] ?? $this->post->excerpt,
            'image' => $this->post->seo_meta['og_image'] ?? null
        ]);
    }
}
