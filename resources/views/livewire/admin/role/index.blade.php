<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Roles</h2>
            <p class="text-blue-300/60 mt-1">Define system roles and their permissions.</p>
        </div>
        
        <a href="{{ route('admin.roles.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-lg shadow-blue-600/30 flex items-center justify-center group">
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create Role
        </a>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
        <div class="bg-[#0a101f] border border-blue-900/30 rounded-2xl p-6 shadow-lg group hover:border-blue-500/30 transition flex flex-col">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-400 group-hover:scale-110 transition border border-blue-500/10">
                   <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                
                @if($role->name !== 'super-admin')
                <div class="flex gap-2">
                    <a href="{{ route('admin.roles.edit', $role) }}" class="p-1.5 text-blue-400 hover:text-white bg-blue-900/20 hover:bg-blue-600 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <button wire:confirm="Are you sure?" wire:click="delete({{ $role->id }})" class="p-1.5 text-rose-400 hover:text-white bg-rose-900/20 hover:bg-rose-600 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                @endif
            </div>

            <h3 class="text-xl font-bold text-white mb-2 ucfirst">{{ $role->name }}</h3>
            
            <div class="mt-auto space-y-3 pt-4 border-t border-blue-900/20">
                <div class="flex justify-between text-sm">
                    <span class="text-blue-300/60">Users Assigned</span>
                    <span class="font-bold text-white">{{ $role->users_count }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-blue-300/60">Permissions</span>
                    <span class="font-bold text-white">{{ $role->permissions_count }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
