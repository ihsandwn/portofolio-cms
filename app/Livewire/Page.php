<?php

namespace App\Livewire;

use App\Models\Page as PageModel;
use Livewire\Component;

class Page extends Component
{
    public $slug;
    public $page;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->page = PageModel::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.page')->layout('components.layouts.app');
    }
}
