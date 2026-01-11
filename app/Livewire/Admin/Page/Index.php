<?php

namespace App\Livewire\Admin\Page;

use App\Models\Page;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.page.index', [
            'pages' => Page::all()
        ])->layout('components.layouts.admin');
    }

    public function delete($id)
    {
        Page::find($id)?->delete();
        session()->flash('success', 'Page deleted successfully.');
    }
}
