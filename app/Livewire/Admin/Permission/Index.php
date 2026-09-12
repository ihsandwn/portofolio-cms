<?php

namespace App\Livewire\Admin\Permission;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

#[Layout('components.layouts.admin')]
#[Title('Permissions List')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.admin.permission.index', [
            'permissions' => Permission::all()
        ]);
    }
}
