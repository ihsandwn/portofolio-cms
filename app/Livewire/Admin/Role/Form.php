<?php

namespace App\Livewire\Admin\Role;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.admin')]
#[Title('Role Form')]
class Form extends Component
{
    public ?Role $role = null;
    public $name = '';
    public $selectedPermissions = [];

    public function mount(Role $role = null)
    {
        if ($role && $role->exists) {
            $this->role = $role;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . ($this->role?->id ?? 'NULL'),
            'selectedPermissions' => 'array'
        ]);

        if ($this->role) {
            $this->role->update(['name' => $this->name]);
        } else {
            $this->role = Role::create(['name' => $this->name]);
        }

        $this->role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Role saved successfully.');
        return redirect()->route('admin.roles.index');
    }

    public function render()
    {
        return view('livewire.admin.role.form', [
            'permissions' => Permission::all()
        ]);
    }
}
