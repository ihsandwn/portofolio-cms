<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-headline font-bold text-on-background tracking-tight">{{ __('Permissions') }}</h2>
            <p class="text-secondary mt-1">{{ __('Available system permissions (Defined in Code).') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($permissions as $permission)
        <div class="bg-surface-container-lowest border border-outline-variant/20 p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-primary-container/50 flex items-center justify-center text-on-primary-container border border-outline-variant/20 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <span class="text-on-background font-medium text-sm">{{ $permission->name }}</span>
        </div>
        @endforeach
    </div>
</div>
