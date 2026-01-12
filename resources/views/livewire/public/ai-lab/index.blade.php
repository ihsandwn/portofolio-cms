<div class="max-w-7xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
     <div class="relative p-1 rounded-3xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 mb-16">
        <div class="bg-slate-900/90 backdrop-blur-xl rounded-2xl p-8 md:p-12 text-center">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-6">
                <span class="w-2 h-2 rounded-full bg-cyan-400 mr-2 animate-pulse"></span> Experimental Zone
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">AI Laboratory</h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">Exploring the boundaries of Autonomous Agents and Large Language Models.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($projects as $project)
        <a href="{{ route('ai-lab.show', $project->slug) }}" class="group relative bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden hover:border-cyan-500/50 transition duration-300 flex flex-col h-full hover:shadow-[0_0_30px_rgba(6,182,212,0.15)]">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition duration-500"></div>
            
            <div class="p-8 relative z-10 flex flex-col h-full">
                <div class="flex items-start justify-between mb-6">
                    <div class="p-3 bg-slate-800 rounded-lg text-cyan-400 group-hover:bg-cyan-900/20 transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <span class="text-xs font-mono text-cyan-500/70 border border-cyan-500/20 px-2 py-1 rounded">{{ $project->completed_at?->format('M Y') }}</span>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-cyan-300 transition">{{ $project->title }}</h3>
                <p class="text-slate-400 mb-6 flex-1">{{ $project->description }}</p>

                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->tech_stack ?? [] as $tech)
                        <span class="text-xs font-medium text-slate-300 bg-slate-800 px-2 py-1 rounded border border-slate-700">{{ $tech }}</span>
                        @endforeach
                    </div>
                    
                    <div class="pt-4 border-t border-slate-800 flex items-center text-cyan-400 text-sm font-bold group-hover:translate-x-2 transition">
                        See Demonstration <span class="ml-2">→</span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
