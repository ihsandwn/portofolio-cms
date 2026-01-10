<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.admin')]
#[Title('User Form')]
class Form extends Component
{
    public ?User $user = null;

    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRoles = [];

    public function mount(User $user = null)
    {
        if ($user && $user->exists) {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user?->id)],
            'password' => $this->user ? 'nullable|min:8' : 'required|min:8',
            'selectedRoles' => 'array'
        ]);

        if ($this->user) {
            $this->user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $this->user->update(['password' => bcrypt($this->password)]);
            }
        } else {
            $this->user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);
        }

        $this->user->syncRoles($this->selectedRoles);

        session()->flash('success', 'User saved successfully.');
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.user.form', [
            'roles' => Role::all()
        ]);
    }
}
