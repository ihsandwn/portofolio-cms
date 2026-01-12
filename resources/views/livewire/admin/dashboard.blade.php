<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stats Card 1 -->
        <div class="bg-[#0a101f] p-6 rounded-2xl border border-blue-900/30 hover:border-blue-500/50 transition duration-300 relative overflow-hidden group shadow-lg shadow-black/20">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-blue-600/10 rounded-full blur-2xl group-hover:bg-blue-600/20 transition-all duration-500"></div>
            
            <h3 class="text-blue-400/80 text-xs font-bold uppercase tracking-widest mb-1">Total Portfolios</h3>
            <div class="flex items-end justify-between mt-2 relative z-10">
                <p class="text-4xl font-black text-white tracking-tight">{{ \App\Models\Portfolio::count() }}</p>
                <div class="p-2.5 bg-blue-900/20 rounded-xl text-blue-400 border border-blue-500/10 group-hover:border-blue-500/30 transition">
                     <svg class="w-6 h-6 transform group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-300/60">
                <span class="text-emerald-400 font-bold mr-1 flex items-center"><svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> 12%</span> vs last month
            </div>
        </div>

        <!-- Stats Card 2 -->
        <div class="bg-[#0a101f] p-6 rounded-2xl border border-blue-900/30 hover:border-cyan-500/50 transition duration-300 relative overflow-hidden group shadow-lg shadow-black/20">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all duration-500"></div>
            
            <h3 class="text-blue-400/80 text-xs font-bold uppercase tracking-widest mb-1">Published Blogs</h3>
            <div class="flex items-end justify-between mt-2 relative z-10">
                <p class="text-4xl font-black text-white tracking-tight">{{ \App\Models\Blog::count() }}</p>
                <div class="p-2.5 bg-blue-900/20 rounded-xl text-cyan-400 border border-cyan-500/10 group-hover:border-cyan-500/30 transition">
                     <svg class="w-6 h-6 transform group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-300/60">
                <span class="text-blue-100/60 font-medium">Content Engine Active</span>
            </div>
        </div>

        <!-- Stats Card 3 -->
        <div class="bg-[#0a101f] p-6 rounded-2xl border border-blue-900/30 hover:border-emerald-500/50 transition duration-300 relative overflow-hidden group shadow-lg shadow-black/20">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500"></div>
            
            <h3 class="text-blue-400/80 text-xs font-bold uppercase tracking-widest mb-1">System Status</h3>
            <div class="flex items-end justify-between mt-2 relative z-10">
                <p class="text-4xl font-black text-emerald-400 tracking-tight">OK</p>
                <div class="p-2.5 bg-blue-900/20 rounded-xl text-emerald-400 border border-emerald-500/10 group-hover:border-emerald-500/30 transition">
                     <svg class="w-6 h-6 transform group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-blue-300/60">
                <span class="text-emerald-400 font-bold mr-1 animate-pulse">●</span> All systems operational
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-[#0a101f] rounded-2xl border border-blue-900/30 p-8 shadow-xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-full bg-gradient-to-l from-blue-900/10 to-transparent pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
            <div class="mb-6 md:mb-0">
                <h2 class="text-2xl font-bold text-white mb-2">Welcome back, Admin!</h2>
                <p class="text-blue-300/70 text-sm max-w-md">Your intelligent portfolio is running smoothly. What would you like to build today?</p>
            </div>
            
            <div class="flex flex-wrap gap-4">
                 <a href="{{ route('admin.portfolios.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold transition shadow-lg shadow-blue-600/30 flex items-center group hover:-translate-y-0.5 transform duration-200">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Portfolio
                 </a>
                 <a href="{{ route('admin.blog.create') }}" class="px-6 py-3 bg-[#0f1525] hover:bg-blue-900/30 text-blue-200 border border-blue-900/50 rounded-xl font-bold transition flex items-center group hover:border-blue-500/30 hover:shadow-lg hover:shadow-blue-500/10 hover:-translate-y-0.5 transform duration-200">
                    <svg class="w-5 h-5 mr-2 group-hover:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Write Blog
                 </a>
            </div>
        </div>
    </div>
</div>
