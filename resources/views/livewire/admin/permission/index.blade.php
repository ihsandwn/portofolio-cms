<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Permissions</h2>
            <p class="text-blue-300/60 mt-1">Available system permissions (Defined in Code).</p>
        </div>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($permissions as $permission)
        <div class="bg-[#0a101f] border border-blue-900/30 rounded-xl p-4 flex items-center shadow-lg">
            <div class="w-10 h-10 bg-blue-600/10 rounded-lg flex items-center justify-center text-blue-400 mr-3 border border-blue-500/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            </div>
            <span class="text-blue-200 font-medium">{{ $permission->name }}</span>
        </div>
        @endforeach
    </div>
</div>
