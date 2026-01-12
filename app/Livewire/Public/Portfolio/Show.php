<?php

namespace App\Livewire\Public\Portfolio;

use Livewire\Component;
use App\Models\Portfolio;

class Show extends Component
{
    public $portfolio;

    public function mount($slug)
    {
        $this->portfolio = Portfolio::where('slug', $slug)
            ->whereIn('type', ['web', 'consulting'])
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.portfolio.show')
            ->layout('components.layouts.app', [
                'title' => $this->portfolio->title,
                'description' => $this->portfolio->description
            ]);
    }
}
