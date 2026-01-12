<div class="max-w-4xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800/50 rounded-3xl p-8 md:p-12 border border-gray-700/50 backdrop-blur-sm">
        <header class="mb-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">{{ $page->title }}</h1>
            <div class="w-24 h-1 bg-gradient-to-r from-indigo-500 to-cyan-400 mx-auto rounded-full"></div>
        </header>
        
        <div class="prose prose-lg prose-invert mx-auto max-w-none text-gray-300">
            {!! $page->content !!}
        </div>
        
        <div class="mt-12 flex justify-center">
             <a href="mailto:ichsanworkthings@gmail.com" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition shadow-lg shadow-indigo-500/25 flex items-center">
                Contact Me
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"></path></svg>
             </a>
        </div>
    </div>
</div>
