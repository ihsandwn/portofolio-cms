<?php

namespace App\Livewire\Public\Blog;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = BlogPost::published()
            ->latest('published_at')
            ->paginate(9);

        return view('livewire.public.blog.index', [
            'posts' => $posts
        ])->layout('components.layouts.app', [
            'title' => 'Blog',
            'description' => 'Latest articles, tutorials, and updates.'
        ]);
    }
}
