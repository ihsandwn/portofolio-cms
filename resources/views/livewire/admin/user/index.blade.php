<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-headline font-bold text-on-background tracking-tight">{{ __('Users') }}</h2>
            <p class="text-secondary mt-1">{{ __('Manage system access and roles.') }}</p>
        </div>

        <a href="{{ route('admin.users.create') }}" wire:navigate class="px-5 py-2.5 bg-primary hover:bg-primary-dim text-on-primary font-label text-[10px] tracking-widest uppercase transition-colors duration-blueprint inline-flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            {{ __('Create User') }}
        </a>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/20 p-4 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search users by name or email...') }}" class="w-full bg-surface text-on-background border border-outline-variant/20 pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-primary focus:border-primary placeholder:text-on-surface-variant transition-shadow duration-blueprint">
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/20 font-label text-[10px] font-bold uppercase tracking-wider text-primary">
                        <th class="px-6 py-4">{{ __('Name') }}</th>
                        <th class="px-6 py-4">{{ __('Role') }}</th>
                        <th class="px-6 py-4">{{ __('Joined') }}</th>
                        <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse($users as $user)
                    <tr class="hover:bg-surface-container-low/80 transition-colors duration-blueprint">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-container flex items-center justify-center text-on-primary-container font-bold font-label border border-outline-variant/20 mr-4">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-on-background">{{ $user->name }}</p>
                                    <p class="text-sm text-secondary">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="px-2.5 py-1 font-label text-[10px] uppercase tracking-wider bg-primary-container/40 text-on-primary-container border border-outline-variant/20">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span class="px-2.5 py-1 font-label text-[10px] uppercase tracking-wider bg-surface-container-high text-secondary border border-outline-variant/20">{{ __('User') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary tabular-nums">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" wire:navigate class="p-2 text-primary hover:bg-surface-container-low border border-transparent hover:border-outline-variant/20 transition-colors duration-blueprint" aria-label="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <button type="button" wire:confirm="{{ __('Are you sure you want to delete this user?') }}" wire:click="delete({{ $user->id }})" class="p-2 text-error hover:bg-error-container/20 border border-transparent transition-colors duration-blueprint" aria-label="{{ __('Delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-secondary">
                            {{ __('No users found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-outline-variant/20">
            {{ $users->links() }}
        </div>
    </div>
</div>
