<?php

namespace App\Livewire\Admin\Menu;

use App\Models\Menu;
use App\Models\MenuItem;
use Livewire\Component;

class Builder extends Component
{
    public Menu $menu;
    public $items;
    
    // Form properties
    public $showForm = false;
    public $editingItem = null;
    public $title = [];
    public $url;
    public $order = 0;

    protected function rules()
    {
        return [
            'title.en' => 'required|string',
            'title.id' => 'nullable|string',
            'url' => 'required|string',
            'order' => 'integer',
        ];
    }

    public function mount(Menu $menu)
    {
        $this->menu = $menu;
        $this->loadItems();
    }

    public function loadItems()
    {
        $this->items = $this->menu->items()->with('children')->get();
    }

    public function create()
    {
        $this->editingItem = null;
        $this->title = ['en' => '', 'id' => ''];
        $this->url = '';
        $this->order = 0;
        $this->showForm = true;
    }

    public function edit(MenuItem $item)
    {
        $this->editingItem = $item;
        $this->title = $item->title->getArrayCopy();
        $this->url = $item->url;
        $this->order = $item->order;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'menu_id' => $this->menu->id,
            'title' => $this->title,
            'url' => $this->url,
            'order' => $this->order,
        ];

        if ($this->editingItem) {
            $this->editingItem->update($data);
        } else {
            MenuItem::create($data);
        }

        $this->showForm = false;
        $this->loadItems();
        session()->flash('success', 'Menu item saved successfully.');
    }

    public function delete($id)
    {
        $item = MenuItem::find($id);
        if ($item) {
            $item->delete();
            $this->loadItems();
            session()->flash('success', 'Menu item deleted.');
        }
    }

    public function render()
    {
        return view('livewire.admin.menu.builder')->layout('components.layouts.admin');
    }
}
