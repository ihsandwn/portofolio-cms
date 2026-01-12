<?php

namespace App\Livewire\Public\Services;

use Livewire\Component;
use App\Models\Service;

class Index extends Component
{
    public function render()
    {
        return view('livewire.public.services.index', [
            'services' => Service::all()
        ])->layout('components.layouts.app', [
            'title' => 'Services',
            'description' => 'Professional Web Development and AI Solutions.'
        ]);
    }
}
