<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
use Illuminate\Support\Str;
use Livewire\Component;

class Form extends Component
{
    public Service $service;
    public $title = [];
    public $description = [];
    public $icon;
    public $category = 'web_dev';
    public $is_edit = false;

    protected function rules()
    {
        return [
            'title.en' => 'required|string|min:3',
            'title.id' => 'nullable|string',
            'description.en' => 'nullable|string',
            'description.id' => 'nullable|string',
            'icon' => 'required|string', // Assuming SVG string for now
            'category' => 'required|in:web_dev,ai_solution,system_arch',
        ];
    }

    public function mount(Service $service = null)
    {
        if ($service && $service->exists) {
            $this->service = $service;
            $this->is_edit = true;
            $this->title = $service->title->getArrayCopy();
            $this->description = $service->description ? $service->description->getArrayCopy() : [];
            $this->icon = $service->icon;
            $this->category = $service->category;
        } else {
            $this->service = new Service();
        }
    }

    public function save()
    {
        $this->validate();

        $this->service->title = $this->title;
        $this->service->slug = Str::slug($this->title['en']); // Generate slug from EN title
        $this->service->description = $this->description;
        $this->service->icon = $this->icon;
        $this->service->category = $this->category;

        $this->service->save();

        session()->flash('success', 'Service saved successfully.');
        return redirect()->route('admin.services.index');
    }

    public function render()
    {
        return view('livewire.admin.service.form')->layout('components.layouts.admin');
    }
}
