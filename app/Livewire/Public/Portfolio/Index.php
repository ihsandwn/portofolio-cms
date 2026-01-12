<?php

namespace App\Livewire\Public\Portfolio;

use Livewire\Component;
use App\Models\Portfolio;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $portfolios = Portfolio::where('is_featured', true)
            ->latest('completed_at')
            ->paginate(9);

        return view('livewire.public.portfolio.index', [
            'portfolios' => $portfolios
        ])->layout('components.layouts.app', [
            'title' => 'Portfolio',
            'description' => 'Selected web development and architecture projects.'
        ]);
    }
}
