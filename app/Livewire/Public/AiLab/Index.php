<?php

namespace App\Livewire\Public\AiLab;

use Livewire\Component;
use App\Models\Portfolio;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $projects = Portfolio::where('type', 'ai_agent')
            ->latest('completed_at')
            ->paginate(9);

        return view('livewire.public.ai-lab.index', [
            'projects' => $projects
        ])->layout('components.layouts.app', [
            'title' => __('AI Lab'),
            'description' => __('AI Lab Description'),
        ]);
    }
}
