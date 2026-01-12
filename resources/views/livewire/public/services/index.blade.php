<div class="max-w-7xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Expertise & Services</h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto">Delivering high-impact technical solutions tailored to your business needs.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($services as $service)
        <div class="bg-gray-800/50 p-8 rounded-2xl border border-gray-700/50 hover:border-indigo-500/30 transition group hover:bg-gray-800">
            <div class="w-14 h-14 bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-400 mb-6 group-hover:scale-110 transition duration-300 ring-1 ring-indigo-500/20">
                {!! $service->icon !!}
            </div>
            <h3 class="text-2xl font-bold text-white mb-4 group-hover:text-indigo-300 transition">{{ $service->title }}</h3>
            <p class="text-gray-400 leading-relaxed mb-6">
                {{ $service->description }}
            </p>
            <div class="mt-auto pt-6 border-t border-gray-700/50">
                <span class="text-sm font-medium text-indigo-400 uppercase tracking-widest">{{ str_replace('_', ' ', $service->category) }}</span>
            </div>
        </div>
        @endforeach
    </div>
</div>
