<?php

namespace App\Livewire\Admin\Portfolio;

use App\Models\Portfolio;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();
        $this->dispatch('notify', 'Portfolio deleted successfully!');
    }

    public function render()
    {
        $portfolios = Portfolio::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.portfolio.index', [
            'portfolios' => $portfolios
        ])->layout('components.layouts.admin', ['title' => 'Portfolios']);
    }
}
