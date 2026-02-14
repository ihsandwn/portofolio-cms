<div class="max-w-6xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8 text-sm text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2">
            <li><a href="/" class="hover:text-cyan-400 transition">Home</a></li>
            <li>/</li>
            <li><a href="{{ route('ai-lab.index') }}" class="hover:text-cyan-400 transition">AI Lab</a></li>
            <li>/</li>
            <li class="text-white truncate max-w-xs">{{ $project->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2">
            <header class="mb-8">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-4">
                     Project {{ $project->slug }}
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">{{ $project->title }}</h1>
                <p class="text-xl text-slate-300 leading-relaxed">{{ $project->description }}</p>
            </header>

            <div class="bg-slate-800/50 rounded-2xl p-8 border border-slate-700/50 mb-12">
                 <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <span class="w-8 h-8 bg-cyan-500/20 text-cyan-400 rounded-lg flex items-center justify-center mr-3 text-sm">01</span>
                    Demonstration / Case Study
                </h2>
                <div class="prose prose-lg prose-invert max-w-none prose-headings:text-cyan-400 prose-a:text-indigo-400">
                    @safeHtml(Str::markdown($project->case_study ?? ''))
                </div>
            </div>
        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-6">
            <!-- Stats -->
            <div class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                <h3 class="text-lg font-bold text-white mb-4">Model Performance</h3>
                <div class="space-y-4">
                    @foreach($project->meta_data ?? [] as $key => $value)
                    <div class="flex justify-between items-center pb-3 border-b border-slate-800 last:border-0">
                        <span class="text-slate-400 text-sm capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        <span class="text-cyan-300 font-mono font-bold">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Stack -->
            <div class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-xl">
                 <h3 class="text-lg font-bold text-white mb-4">Tech Stack</h3>
                 <div class="flex flex-wrap gap-2">
                    @foreach($project->tech_stack ?? [] as $tech)
                    <span class="px-3 py-1 bg-slate-800 text-indigo-300 rounded-lg text-sm border border-slate-700">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>

             @if($project->repo_url || $project->url)
            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 rounded-2xl p-6 border border-indigo-500/30">
                <h3 class="text-lg font-bold text-white mb-4">Access</h3>
                <div class="flex flex-col gap-3">
                    @if($project->url)
                        @if($accessRequested)
                            <div class="bg-gradient-to-r from-sky-500/20 to-blue-500/20 border border-sky-500/40 rounded-xl p-4 text-center animate-pulse">
                                <h4 class="text-sky-400 font-bold text-lg mb-2">Access Granted! 🚀</h4>
                                <p class="text-slate-300 text-sm mb-4">Your secure session is ready.</p>
                                <a href="{{ $generatedUrl }}" target="_blank" class="block w-full px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold transition shadow-lg shadow-blue-500/20">
                                    Continue to App &rarr;
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                <div class="relative">
                                    <input 
                                        type="email" 
                                        wire:model="email" 
                                        placeholder="Enter your email to access..." 
                                        class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 transition"
                                    >
                                    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <button 
                                    wire:click="requestAccess" 
                                    wire:loading.attr="disabled"
                                    class="w-full flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold transition disabled:opacity-50 disabled:cursor-not-allowed group relative overflow-hidden"
                                >
                                    <span wire:loading.remove class="group-hover:translate-x-1 transition-transform">Request Access 🔒</span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Verifying...
                                    </span>
                                </button>
                                <p class="text-xs text-slate-500 text-center">
                                    *Instant temporary access for demo.
                                </p>
                            </div>
                        @endif
                    @endif

                    @if($project->repo_url)
                     <a href="{{ $project->repo_url }}" target="_blank" class="w-full text-center px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg font-bold transition border border-slate-700 mt-2">
                        View Code
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
