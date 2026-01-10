<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-3xl font-bold text-white tracking-tight">{{ $user ? 'Edit User' : 'Create User' }}</h2>
        <p class="text-blue-300/60 mt-1">Manage user details and role assignments.</p>
    </div>

    <!-- Form -->
    <form wire:submit="save" class="bg-[#0a101f] border border-blue-900/30 rounded-2xl p-6 md:p-8 shadow-xl space-y-6">
        
        <!-- Name -->
        <div>
            <label class="block text-sm font-semibold text-blue-200 mb-2">Full Name</label>
            <input wire:model="name" type="text" class="w-full bg-[#050b14] text-white border border-blue-900/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition placeholder-blue-500/20">
            @error('name') <span class="text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-semibold text-blue-200 mb-2">Email Address</label>
            <input wire:model="email" type="email" class="w-full bg-[#050b14] text-white border border-blue-900/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition placeholder-blue-500/20">
            @error('email') <span class="text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-semibold text-blue-200 mb-2">Password {{ $user ? '(Leave blank to keep current)' : '' }}</label>
            <input wire:model="password" type="password" class="w-full bg-[#050b14] text-white border border-blue-900/40 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition placeholder-blue-500/20">
            @error('password') <span class="text-rose-400 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Roles -->
        <div>
            <label class="block text-sm font-semibold text-blue-200 mb-2">Assign Roles</label>
            <div class="grid grid-cols-2 gap-3">
                @foreach($roles as $role)
                <label class="flex items-center space-x-3 p-3 rounded-xl border border-blue-900/30 bg-[#050b14] hover:bg-blue-900/10 cursor-pointer transition">
                    <input wire:model="selectedRoles" type="checkbox" value="{{ $role->name }}" class="rounded bg-blue-900/20 border-blue-500/30 text-blue-600 focus:ring-blue-500/50 focus:ring-offset-0 w-5 h-5">
                    <span class="text-blue-100 font-medium">{{ $role->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-blue-900/30 flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-[#050b14] hover:bg-blue-900/20 text-blue-300 rounded-xl font-bold transition border border-transparent hover:border-blue-900/50">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-lg shadow-blue-600/30 flex items-center">
                <span wire:loading.remove>Save User</span>
                <span wire:loading class="animate-pulse">Saving...</span>
            </button>
        </div>
    </form>
</div>
