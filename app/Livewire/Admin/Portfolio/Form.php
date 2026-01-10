<?php

namespace App\Livewire\Admin\Portfolio;

use App\Models\Portfolio;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public Portfolio $portfolio;
    public $tech_stack_input = ''; 
    public $is_edit = false;

    // Form Fields
    public $title;
    public $slug;
    public $description;
    public $type = 'web';
    public $client;
    public $url;
    public $repo_url;
    public $case_study;
    public $is_featured = false;

    // Image upload (TBD)
    
    protected function rules()
    {
        return [
            'title' => 'required|min:3',
            'slug' => 'required|unique:portfolios,slug,' . ($this->portfolio->id ?? 'NULL'),
            'description' => 'nullable|max:255',
            'type' => 'required|in:web,ai_agent,consulting',
            'client' => 'nullable|string',
            'url' => 'nullable|url',
            'repo_url' => 'nullable|url',
            'tech_stack_input' => 'nullable|string', // "Laravel, Vue, AI"
            'case_study' => 'nullable|string',
            'is_featured' => 'boolean',
        ];
    }

    public function mount(Portfolio $portfolio = null)
    {
        if ($portfolio->exists) {
            $this->portfolio = $portfolio;
            $this->is_edit = true;
            $this->fill($portfolio->only([
                'title', 'slug', 'description', 'type', 'client', 'url', 'repo_url', 'case_study', 'is_featured'
            ]));
            $this->tech_stack_input = implode(', ', $portfolio->tech_stack ?? []);
        } else {
            $this->portfolio = new Portfolio();
        }
    }

    public function updatedTitle($value)
    {
        if (!$this->is_edit) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $validated = $this->validate();

        // Process Tech Stack
        $tech_stack = array_map('trim', explode(',', $this->tech_stack_input));
        $tech_stack = array_filter($tech_stack); // Remove empty strings

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'client' => $this->client,
            'url' => $this->url,
            'repo_url' => $this->repo_url,
            'tech_stack' => $tech_stack,
            'case_study' => $this->case_study,
            'is_featured' => $this->is_featured,
            'completed_at' => now(), // Default for now
        ];

        if ($this->is_edit) {
            $this->portfolio->update($data);
            session()->flash('notify', 'Portfolio updated successfully!');
        } else {
            Portfolio::create($data);
            session()->flash('notify', 'Portfolio created successfully!');
        }

        return redirect()->route('admin.portfolios.index');
    }

    public function render()
    {
        return view('livewire.admin.portfolio.form')
            ->layout('components.layouts.admin', [
                'title' => $this->is_edit ? 'Edit Portfolio' : 'New Portfolio'
            ]);
    }
}
