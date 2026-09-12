<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-headline font-bold text-on-background tracking-tight">{{ __('Roles') }}</h2>
            <p class="text-secondary mt-1">{{ __('Define system roles and their permissions.') }}</p>
        </div>

        <a href="{{ route('admin.roles.create') }}" wire:navigate class="px-5 py-2.5 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint inline-flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            {{ __('Create Role') }}
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
        <div class="bg-surface-container-lowest border border-outline-variant/20 p-6 group hover:border-outline-variant/40 transition-colors duration-blueprint flex flex-col">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-primary-container/50 flex items-center justify-center text-on-primary-container border border-outline-variant/20">
                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>

                @if($role->name !== 'super-admin')
                <div class="flex gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" wire:navigate class="p-1.5 text-primary hover:bg-surface-container-low border border-transparent hover:border-outline-variant/20 transition-colors duration-blueprint" aria-label="{{ __('Edit') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <button type="button" wire:confirm="{{ __('Are you sure?') }}" wire:click="delete({{ $role->id }})" class="p-1.5 text-error hover:bg-error-container/20 transition-colors duration-blueprint" aria-label="{{ __('Delete') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                @endif
            </div>

            <h3 class="text-xl font-headline font-bold text-on-background mb-2 capitalize">{{ $role->name }}</h3>

            <div class="mt-auto space-y-3 pt-4 border-t border-outline-variant/20">
                <div class="flex justify-between text-sm">
                    <span class="text-secondary">{{ __('Users Assigned') }}</span>
                    <span class="font-bold text-on-background tabular-nums">{{ $role->users_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-secondary">{{ __('Permissions') }}</span>
                    <span class="font-bold text-on-background tabular-nums">{{ $role->permissions_count }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
