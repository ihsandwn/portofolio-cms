<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
use Livewire\Component;

class Index extends Component
{
    public function delete($id)
    {
        $service = Service::find($id);
        if ($service) {
            $service->delete();
            session()->flash('success', 'Service deleted successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin.service.index', [
            'services' => Service::all()
        ])->layout('components.layouts.admin');
    }
}
