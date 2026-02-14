<div class="max-w-4xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
     <!-- Breadcrumb -->
     <nav class="flex mb-8 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2">
            <li class="inline-flex items-center">
                <a href="/" class="hover:text-blue-600 dark:hover:text-white transition-colors">Home</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li class="inline-flex items-center">
                <a href="{{ route('portfolio.index') }}" class="hover:text-blue-600 dark:hover:text-white transition-colors">Portfolio</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li aria-current="page">
                <span class="text-gray-900 dark:text-white font-medium truncate max-w-[200px] sm:max-w-md block">{{ $portfolio->title }}</span>
            </li>
        </ol>
    </nav>

    <header class="mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
            <h1 class="text-4xl md:text-5xl font-bold text-white">{{ $portfolio->title }}</h1>
            @if($portfolio->url)
            <a href="{{ $portfolio->url }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-medium transition">
                Visit Site <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
            @endif
        </div>
        
        <div class="flex flex-wrap gap-3 mb-8">
            <span class="px-3 py-1 rounded-full bg-gray-800 text-indigo-400 text-sm border border-gray-700">Client: {{ $portfolio->client ?? 'Confidential' }}</span>
            <span class="px-3 py-1 rounded-full bg-gray-800 text-gray-300 text-sm border border-gray-700">Completed: {{ $portfolio->completed_at?->format('F Y') ?? 'Ongoing' }}</span>
        </div>

        <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700/50">
             <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-widest mb-4">Tech Stack</h3>
             <div class="flex flex-wrap gap-2">
                @foreach($portfolio->tech_stack ?? [] as $tech)
                <span class="px-3 py-1 bg-indigo-500/10 text-indigo-300 rounded-lg text-sm border border-indigo-500/20">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </header>

    <article class="prose prose-lg prose-invert max-w-none">
        @safeHtml(Str::markdown($portfolio->case_study ?? ''))
    </article>
</div>
