<div class="max-w-6xl mx-auto pt-28 md:pt-36 pb-20 px-4 sm:px-6 lg:px-8">
    <header class="rounded-2xl border border-slate-800/80 bg-slate-900/50 p-8 md:p-12 mb-16">
        <p class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-sky-500/10 border border-sky-500/20 text-sky-400 text-sm font-medium mb-6">
            {{ __('Experimental Zone') }}
        </p>
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ __('AI Laboratory') }}</h1>
        <p class="text-lg text-slate-400 max-w-2xl">{{ __('AI Lab Subtitle') }}</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($projects as $project)
        <a href="{{ route('ai-lab.show', $project->slug) }}" class="group rounded-xl border border-slate-800/80 bg-slate-900/30 overflow-hidden hover:border-slate-700 transition flex flex-col h-full">
            <div class="p-8 flex-1 flex flex-col">
                <div class="flex items-start justify-between mb-6">
                    <div class="w-12 h-12 rounded-lg bg-sky-500/10 flex items-center justify-center text-sky-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    @if($project->completed_at)
                    <span class="text-xs font-mono text-slate-500">{{ $project->completed_at->format('M Y') }}</span>
                    @endif
                </div>
                <h2 class="text-xl font-semibold text-white mb-3 group-hover:text-sky-400 transition">{{ $project->title }}</h2>
                <p class="text-slate-400 mb-6 flex-1 line-clamp-3">{{ $project->description }}</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($project->tech_stack ?? [] as $tech)
                    <span class="text-xs bg-slate-800/80 text-slate-400 px-2 py-1 rounded">{{ $tech }}</span>
                    @endforeach
                </div>
                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-sky-400 group-hover:text-sky-300 transition">
                    {{ __('See Demonstration') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </span>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $projects->links() }}
    </div>
</div>
