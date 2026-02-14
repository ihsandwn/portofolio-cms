<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Users</h2>
            <p class="text-blue-300/60 mt-1">Manage system access and roles.</p>
        </div>
        
        <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-lg shadow-blue-600/30 flex items-center justify-center group">
            <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create User
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-[#0a101f] border border-blue-900/30 rounded-2xl p-4 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-blue-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users by name or email..." class="w-full bg-[#050b14] text-blue-100 border border-blue-900/40 rounded-xl pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 placeholder-blue-500/30">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-[#0a101f] border border-blue-900/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-blue-900/10 border-b border-blue-900/30 text-blue-400 text-xs font-bold uppercase tracking-wider">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-900/20">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-900/5 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-600/20 flex items-center justify-center text-blue-400 font-bold border border-blue-500/20 mr-4">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-white">{{ $user->name }}</p>
                                    <p class="text-sm text-blue-400/60">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @foreach($user->roles as $role)
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                            @if($user->roles->isEmpty())
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700">User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-blue-300/60">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-blue-400 hover:text-white bg-blue-900/20 hover:bg-blue-600 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <button wire:confirm="Are you sure you want to delete this user?" wire:click="delete({{ $user->id }})" class="p-2 text-rose-400 hover:text-white bg-rose-900/20 hover:bg-rose-600 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-blue-400/50">
                            No users found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-blue-900/30">
            {{ $users->links() }}
        </div>
    </div>
</div>
