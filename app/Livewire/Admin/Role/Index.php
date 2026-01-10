<?php

namespace App\Livewire\Admin\Role;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.admin')]
#[Title('Role Management')]
class Index extends Component
{
    public function delete(Role $role)
    {
        if ($role->name === 'super-admin') {
            session()->flash('error', 'Cannot delete super-admin role.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.role.index', [
            'roles' => Role::withCount(['users', 'permissions'])->get()
        ]);
    }
}
