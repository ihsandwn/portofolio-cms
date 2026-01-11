<div>
    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-900/40 border border-indigo-500/30 text-indigo-300 text-xs font-medium mb-6">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse mr-2"></span>
                    {{ $hero['hero_badge'] ?? 'Available for AI & Architecture Consulting' }}
                </div>
                
                <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight mb-6">
                    {{ $hero['hero_title_prefix'] ?? 'Building the' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">{{ $hero['hero_title_highlight'] ?? 'Future' }}</span> <br class="hidden md:block"/>
                    {{ $hero['hero_title_suffix'] ?? 'Intelligent Web Systems' }}
                </h1>
                
                <p class="text-xl text-slate-400 mb-10 leading-relaxed max-w-2xl mx-auto">
                    {{ $hero['hero_description'] ?? 'Senior Full-Stack Architect...' }}
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#portfolio" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-semibold transition shadow-lg shadow-indigo-500/25 flex items-center justify-center">
                        {{ $hero['hero_cta_projects'] ?? 'View Projects' }}
                    </a>
                    <a href="https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114" target="_blank" class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-semibold border border-slate-700 transition flex items-center justify-center">
                        {{ $hero['hero_cta_cv'] ?? 'Download CV' }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-3xl z-0 opacity-50 animate-pulse" style="animation-duration: 4s;"></div>
    </section>

    <!-- About Section (CMS Content) -->
    <section id="about" class="py-24 bg-slate-900 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-cyan-500/5 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">{{ $aboutPage->title ?? 'About Me' }}</h2>
                <div class="prose prose-lg prose-invert mx-auto text-slate-400">
                    {!! $aboutPage->content ?? '' !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Expertise Section -->
    <section id="services" class="py-20 bg-slate-900/50 border-t border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Core Expertise</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-indigo-500 to-cyan-400 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="bg-slate-800/50 p-8 rounded-2xl border border-slate-700/50 hover:border-indigo-500/30 transition group">
                    <div class="w-12 h-12 bg-indigo-500/10 rounded-lg flex items-center justify-center text-indigo-400 mb-6 group-hover:scale-110 transition">
                        {!! $service->icon !!}
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">{{ $service->title }}</h3>
                    <p class="text-slate-400 leading-relaxed">
                        {{ $service->description }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-white mb-4">Selected Work</h2>
                    <p class="text-slate-400 max-w-xl">
                        A curated showcase of complex problem solving, from government portals to experimental AI workflows.
                    </p>
                </div>
                
                <!-- Filter -->
                <div class="flex space-x-2 mt-6 md:mt-0 bg-slate-800/50 p-1 rounded-lg">
                    <button wire:click="setFilter('all')" class="px-4 py-2 rounded-md text-sm font-medium transition {{ $filter === 'all' ? 'bg-slate-700 text-white shadow' : 'text-slate-400 hover:text-white' }}">All</button>
                    <button wire:click="setFilter('web')" class="px-4 py-2 rounded-md text-sm font-medium transition {{ $filter === 'web' ? 'bg-slate-700 text-white shadow' : 'text-slate-400 hover:text-white' }}">Web Dev</button>
                    <button wire:click="setFilter('ai')" class="px-4 py-2 rounded-md text-sm font-medium transition {{ $filter === 'ai' ? 'bg-cyan-900/50 text-cyan-300 shadow border border-cyan-500/20' : 'text-slate-400 hover:text-white' }}">AI Solutions</button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($portfolios as $portfolio)
                <div class="group bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-slate-600 transition duration-300 flex flex-col h-full">
                    <!-- Placeholder Image (Gradient) -->
                    <div class="h-48 bg-gradient-to-br {{ $portfolio->type === 'ai_agent' ? 'from-slate-800 to-indigo-900/50' : 'from-slate-800 to-slate-700' }} flex items-center justify-center relative overflow-hidden">
                        <span class="text-5xl opacity-10 font-bold text-white group-hover:scale-110 transition duration-500">
                            {{ substr($portfolio->title, 0, 1) }}
                        </span>
                        
                        @if($portfolio->type === 'ai_agent')
                        <div class="absolute top-4 right-4 bg-cyan-500/20 text-cyan-300 text-xs px-2 py-1 rounded border border-cyan-500/20 flex items-center">
                            <span class="w-1 h-1 bg-cyan-400 rounded-full mr-1 animate-pulse"></span> AI Powered
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition">{{ $portfolio->title }}</h3>
                            <p class="text-slate-400 text-sm mt-2 line-clamp-3">{{ $portfolio->description }}</p>
                        </div>
                        
                        <div class="mt-auto">
                            <!-- Tech Stack -->
                            <!-- Description ends here -->
                            
                            <a href="#" class="inline-flex items-center text-sm font-medium text-indigo-400 hover:text-indigo-300 transition">
                                View Case Study <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- AI Lab Section (CMS Content) -->
    <section id="ai-lab" class="py-24 relative bg-slate-950 overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="relative p-1 rounded-3xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                <div class="bg-slate-900/90 backdrop-blur-xl rounded-2xl p-8 md:p-12 border border-white/10 shadow-2xl">
                    <div class="flex flex-col md:flex-row gap-12 items-center">
                        <div class="flex-1">
                             <div class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-6">
                                <span class="w-2 h-2 rounded-full bg-cyan-400 mr-2 animate-pulse"></span> Experimental
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">{{ $aiPage->title ?? 'AI Lab' }}</h2>
                            <div class="prose prose-lg prose-invert text-slate-300">
                                {!! $aiPage->content ?? '' !!}
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex justify-center">
                             <!-- Decorative UI Element -->
                            <div class="relative w-48 h-48">
                                <div class="absolute inset-0 border-2 border-indigo-500/30 rounded-full animate-[spin_10s_linear_infinite]"></div>
                                <div class="absolute inset-4 border-2 border-purple-500/30 rounded-full animate-[spin_15s_linear_infinite_reverse]"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-cyan-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-16 bg-slate-800/30 rounded-2xl p-8 border border-slate-800 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-2">Ready to modernize your architecture?</h3>
                        <p class="text-slate-400">Let's discuss how AI agents can automate your workflows</p>
                    </div>
                    <a href="mailto:ichsanworkthings@gmail.com" class="mt-6 md:mt-0 px-6 py-3 bg-white text-slate-900 font-bold rounded-lg hover:bg-slate-200 transition">
                        Start a Conversation
                    </a>
                </div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            </div>
        </div>
    </section>
</div>
