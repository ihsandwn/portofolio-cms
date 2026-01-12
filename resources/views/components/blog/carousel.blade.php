@props(['images'])

@php
    $images = is_array($images) ? $images : [];
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
        <div x-data="{ activeSlide: 0, slides: {{ json_encode(array_map(fn($i) => asset('storage/'.$i), $images)) }} }" class="relative rounded-lg overflow-hidden shadow-md">
            
            <!-- Slides -->
            <div class="relative w-full h-96 bg-gray-100 dark:bg-gray-800">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute inset-0 flex items-center justify-center">
                        <img :src="slide" class="w-full h-full object-contain">
                    </div>
                </template>
            </div>

            <!-- Controls -->
            <button @click="activeSlide = activeSlide === 0 ? slides.length - 1 : activeSlide - 1" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70">
                &larr;
            </button>
            <button @click="activeSlide = activeSlide === slides.length - 1 ? 0 : activeSlide + 1" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70">
                &rarr;
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="activeSlide = index" 
                            :class="{'bg-white': activeSlide === index, 'bg-white/50': activeSlide !== index}" 
                            class="w-3 h-3 rounded-full transition"></button>
                </template>
            </div>
        </div>
    @endif
</div>
