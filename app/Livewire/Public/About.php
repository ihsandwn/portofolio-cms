<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Page;

class About extends Component
{
    public function render()
    {
        $page = Page::where('slug', 'about')->firstOrFail();

        return view('livewire.public.about', [
            'page' => $page
        ])->layout('components.layouts.app', [
            'title' => $page->title,
            'description' => strip_tags($page->content)
        ]);
    }
}
