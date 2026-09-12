@php
    $f = 'w-full bg-surface-container-lowest border border-outline-variant/20 px-4 py-3 text-on-background placeholder:text-on-surface-variant focus:ring-2 focus:ring-primary focus:border-primary';
@endphp
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h2 class="text-3xl font-headline font-bold text-on-background tracking-tight">{{ $role ? __('Edit Role') : __('Create Role') }}</h2>
        <p class="text-secondary mt-1">{{ __('Manage role scope and permissions.') }}</p>
    </div>

    <form wire:submit="save" class="bg-surface-container-lowest border border-outline-variant/20 p-6 md:p-8 space-y-6">
        <div>
            <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-2">{{ __('Role Name') }}</label>
            <input wire:model="name" type="text" class="{{ $f }}" @if($role && $role->name === 'super-admin') disabled @endif>
            @error('name') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-label text-[10px] font-semibold uppercase tracking-wider text-secondary mb-4">{{ __('Assign Permissions') }}</label>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($permissions as $permission)
                <label class="flex items-center gap-3 p-3 border border-outline-variant/20 bg-surface-container-low hover:bg-surface-container cursor-pointer transition-colors duration-blueprint">
                    <input wire:model="selectedPermissions" type="checkbox" value="{{ $permission->name }}" class="h-5 w-5 border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                    <span class="text-on-background text-sm font-medium">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="pt-4 border-t border-outline-variant/20 flex justify-end gap-3">
            <a href="{{ route('admin.roles.index') }}" wire:navigate class="px-6 py-3 border border-outline text-on-background font-label text-[10px] uppercase tracking-widest hover:bg-surface-container-low transition-colors duration-blueprint">{{ __('Cancel') }}</a>
            <button type="submit" class="px-8 py-3 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] uppercase tracking-widest transition-colors duration-blueprint inline-flex items-center gap-2">
                <span wire:loading.remove wire:target="save">{{ __('Save Role') }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <span class="inline-block h-3 w-3 border-2 border-on-primary border-t-transparent animate-spin" aria-hidden="true"></span>
                    {{ __('Saving...') }}
                </span>
            </button>
        </div>
    </form>
</div>
