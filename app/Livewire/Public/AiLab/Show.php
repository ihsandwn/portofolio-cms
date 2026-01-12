<?php

namespace App\Livewire\Public\AiLab;

use Livewire\Component;
use App\Models\Portfolio;

class Show extends Component
{
    public $project;

    public function mount($slug)
    {
        $this->project = Portfolio::where('slug', $slug)
            ->where('type', 'ai_agent')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.ai-lab.show')
            ->layout('components.layouts.app', [
                'title' => $this->project->title,
                'description' => $this->project->description
            ]);
    }
}
