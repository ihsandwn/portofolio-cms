@props(['images'])

@php
    $images = is_array($images) ? array_values($images) : [];
    $count = count($images);
@endphp

<div class="blog-gallery mb-8">
    @if($count === 0)
        <!-- No images -->
    @elseif($count === 1)
        <!-- Single Image -->
        <div class="rounded-lg overflow-hidden shadow-md">
             @php $img = $images[0]; $src = is_string($img) ? asset('storage/'.$img) : $img->temporaryUrl(); @endphp
            <img src="{{ $src }}" alt="Blog Image" class="w-full h-auto object-cover" loading="lazy">
        </div>
    @else
        <!-- Carousel (Alpine.js) -->
        <div x-data="{ activeSlide: 0, slides: {{ json_encode(array_map(fn($i) => asset('storage/'.$i), $images)) }} }" class="relative rounded-lg overflow-hidden shadow-md bg-gray-900 group h-[600px]">
            
            <!-- 1. Ambient Background Layer (Fixed behind slides) -->
            <!-- We use a transition on the SRC change or simple crossfade if possible, but for now simple binding is stable -->
            <div class="absolute inset-0 z-0 overflow-hidden">
                <template x-for="(slide, index) in slides" :key="'bg-'+index">
                    <div x-show="activeSlide === index"
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-1000"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img :src="slide" class="w-full h-full object-cover blur-2xl opacity-50 scale-110">
                    </div>
                </template>
            </div>

            <!-- 2. Active Slide Layer -->
            <div class="absolute inset-0 z-10 flex items-center justify-center p-4">
                <template x-for="(slide, index) in slides" :key="'slide-'+index">
                    <div x-show="activeSlide === index" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute inset-0 flex items-center justify-center pointer-events-none"> 
                         <!-- pointer-events-none ensures image doesn't block clicks on overlay controls if any -->
                        
                        <img :src="slide" class="w-full h-full object-contain drop-shadow-2xl pointer-events-auto">
                    </div>
                </template>
            </div>

            <!-- 3. Controls (High Z-Index) -->
            <button @click.stop="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-30 bg-black/40 hover:bg-black/80 text-white p-3 rounded-full backdrop-blur-sm transition-all transform hover:scale-110 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click.stop="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-30 bg-black/40 hover:bg-black/80 text-white p-3 rounded-full backdrop-blur-sm transition-all transform hover:scale-110 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex space-x-2 p-2 rounded-full bg-black/20 backdrop-blur-sm">
                <template x-for="(slide, index) in slides" :key="'dot-'+index">
                    <button @click="activeSlide = index" 
                            :class="{'bg-white w-6': activeSlide === index, 'bg-white/50 w-2 hover:bg-white/80': activeSlide !== index}" 
                            class="h-2 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </div>
    @endif
</div>
