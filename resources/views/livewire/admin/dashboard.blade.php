<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20 hover:border-primary/30 transition-colors duration-blueprint relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-primary-container/40 blur-2xl pointer-events-none"></div>

            <h3 class="font-label text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-1 relative z-10">{{ __('Total Portfolios') }}</h3>
            <div class="flex items-end justify-between mt-2 relative z-10">
                <p class="text-4xl font-headline font-black text-on-background tracking-tight tabular-nums">{{ \App\Models\Portfolio::count() }}</p>
                <div class="p-2.5 bg-surface-container-low text-primary border border-outline-variant/20 group-hover:border-primary/30 transition-colors duration-blueprint">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div class="mt-4 font-label text-[10px] uppercase tracking-wider text-secondary relative z-10">
                <span>{{ __('Manage from Portfolios') }}</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20 hover:border-primary/30 transition-colors duration-blueprint relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-tertiary-container/30 blur-2xl pointer-events-none"></div>

            <h3 class="font-label text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-1 relative z-10">{{ __('Published Blogs') }}</h3>
            <div class="flex items-end justify-between mt-2 relative z-10">
                <p class="text-4xl font-headline font-black text-on-background tracking-tight tabular-nums">{{ \App\Models\Blog::count() }}</p>
                <div class="p-2.5 bg-surface-container-low text-tertiary border border-outline-variant/20 group-hover:border-primary/30 transition-colors duration-blueprint">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>
            <div class="mt-4 font-label text-[10px] uppercase tracking-wider text-secondary relative z-10">
                <span>{{ __('Content Engine Active') }}</span>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 border border-outline-variant/20 hover:border-primary/30 transition-colors duration-blueprint relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-primary-container/30 blur-2xl pointer-events-none"></div>

            <h3 class="font-label text-[10px] font-bold uppercase tracking-[0.2em] text-primary mb-1 relative z-10">{{ __('System Status') }}</h3>
            <div class="flex items-end justify-between mt-2 relative z-10">
                <p class="text-4xl font-headline font-black text-primary tracking-tight">OK</p>
                <div class="p-2.5 bg-surface-container-low text-primary border border-outline-variant/20 group-hover:border-primary/30 transition-colors duration-blueprint">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center font-label text-[10px] uppercase tracking-wider text-secondary relative z-10">
                <span class="text-primary font-bold mr-1 animate-pulse">●</span> {{ __('All systems operational') }}
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant/20 p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-full bg-gradient-to-l from-primary-container/10 to-transparent pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-headline font-bold text-on-background mb-2">{{ __('Welcome back, Admin!') }}</h2>
                <p class="text-secondary text-sm max-w-md">{{ __('Your intelligent portfolio is running smoothly. What would you like to build today?') }}</p>
            </div>

            <div class="flex flex-wrap gap-4">
                 <a href="{{ route('admin.portfolios.create') }}" wire:navigate class="px-6 py-3 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    {{ __('New Portfolio') }}
                 </a>
                 <a href="{{ route('admin.blog.create') }}" wire:navigate class="px-6 py-3 bg-surface-container-low text-on-background font-label text-[10px] tracking-widest uppercase border border-outline hover:bg-surface-container transition-colors duration-blueprint inline-flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    {{ __('Write Blog') }}
                 </a>
            </div>
        </div>
    </div>
</div>
