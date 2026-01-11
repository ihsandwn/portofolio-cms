<?php

namespace App\Livewire\Admin\Page;

use App\Models\Page;
use Livewire\Component;
use Illuminate\Support\Str;

class Form extends Component
{
    public Page $page;
    public $title = [];
    public $content = [];
    public $slug;
    public $is_active = true;

    protected function rules()
    {
        return [
            'title.en' => 'required|string|max:255',
            'title.id' => 'nullable|string|max:255',
            'content.en' => 'required|string',
            'content.id' => 'nullable|string',
            'slug' => 'required|string|max:255|unique:pages,slug,' . ($this->page->id ?? 'NULL'),
            'is_active' => 'boolean',
        ];
    }

    public function mount(Page $page = null)
    {
        if ($page->exists) {
            $this->page = $page;
            $this->title = $page->title->getArrayCopy();
            $this->content = $page->content->getArrayCopy();
            $this->slug = $page->slug;
            $this->is_active = $page->is_active;
        } else {
            $this->page = new Page();
            $this->title = ['en' => '', 'id' => ''];
            $this->content = ['en' => '', 'id' => ''];
        }
    }

    public function updatedTitleEn($value)
    {
        if (!$this->page->exists) {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $this->validate();

        $this->page->fill([
            'slug' => $this->slug,
            'title' => $this->title,
            'content' => $this->content,
            'is_active' => $this->is_active,
        ]);

        $this->page->save();

        session()->flash('success', 'Page saved successfully.');
        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.page.form')->layout('components.layouts.admin');
    }
}
