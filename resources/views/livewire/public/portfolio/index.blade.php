<div class="max-w-7xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Selected Works</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto">A showcase of technical excellence and creative problem solving.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($portfolios as $portfolio)
        <a href="{{ $portfolio->type === 'ai_agent' ? route('ai-lab.show', $portfolio->slug) : route('portfolio.show', $portfolio->slug) }}" class="group bg-gray-900 border border-gray-800 rounded-xl overflow-hidden hover:border-indigo-500/50 transition duration-300 flex flex-col h-full hover:shadow-2xl hover:shadow-indigo-500/10">
            <!-- Placeholder Image -->
            <div class="h-48 bg-gradient-to-br from-gray-800 to-gray-700 flex items-center justify-center relative overflow-hidden">
                 @if($portfolio->image)
                    <img src="{{ Storage::url($portfolio->image) }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                @else
                    <span class="text-6xl opacity-10 font-bold text-white group-hover:scale-110 transition duration-500">
                        {{ substr($portfolio->title, 0, 1) }}
                    </span>
                @endif
                <div class="absolute top-4 right-4 bg-gray-900/80 backdrop-blur text-xs px-2 py-1 rounded border border-gray-700">
                    {{ ucfirst($portfolio->type) }}
                </div>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition mb-2">{{ $portfolio->title }}</h3>
                    <p class="text-gray-400 text-sm line-clamp-3">{{ $portfolio->description }}</p>
                </div>
                
                <div class="mt-auto">
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach(collect($portfolio->tech_stack)->take(3) as $tech)
                        <span class="text-xs bg-gray-800 text-gray-400 px-2 py-1 rounded">{{ $tech }}</span>
                        @endforeach
                    </div>
                    <span class="inline-flex items-center text-sm font-medium text-indigo-400 group-hover:text-indigo-300 transition">
                        View Case Study <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    
    <div class="mt-12">
        {{ $portfolios->links() }}
    </div>
</div>
