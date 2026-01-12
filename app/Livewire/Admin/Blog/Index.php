<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();
        session()->flash('message', 'Post deleted successfully.');
    }

    public function render()
    {
        $posts = BlogPost::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.blog.index', [
            'posts' => $posts
        ])->layout('components.layouts.admin', ['title' => 'Blog Posts']);
    }
}
