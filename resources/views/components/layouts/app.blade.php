@props([
    'title' => null,
    'description' => null,
    'image' => null,
])
@php
    $siteName = 'Ichsan Dwi Nugraha';
    $siteTitle = $title ? $title . ' — ' . $siteName : __('Senior Full-Stack Developer') . ' — ' . $siteName;
    $siteDescription = $description ?? __('Site Description');
    $siteImage = $image ?? asset('favicon.png');
    $canonicalUrl = url()->current();
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $siteName,
        'jobTitle' => __('Architect & AI Specialist'),
        'url' => url('/'),
        'sameAs' => ['https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114'],
        'description' => $siteDescription,
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $siteDescription }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="index, follow">

    {{-- Canonical & alternate languages for SEO --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="en" href="{{ url('/') }}">
    <link rel="alternate" hreflang="id" href="{{ url('/') }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    {{-- Open Graph / Social --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $siteTitle }}">
    <meta property="og:description" content="{{ $siteDescription }}">
    <meta property="og:image" content="{{ $siteImage }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">
    <meta property="og:site_name" content="{{ $siteName }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $siteTitle }}">
    <meta name="twitter:description" content="{{ $siteDescription }}">

    <title>{{ $siteTitle }}</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES) !!}</script>

    <style>
        body { font-family: 'DM Sans', system-ui, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .bg-dots {
            background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased selection:bg-blue-500/30 selection:text-blue-100 flex flex-col min-h-screen">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-blue-500 focus:text-slate-900 focus:rounded-lg focus:outline-none">{{ __('Skip to content') }}</a>

    <div class="fixed inset-0 z-[-1] bg-slate-950 bg-dots pointer-events-none"></div>

    <nav x-data="{ open: false, scrolled: false }"
         x-on:scroll.window="scrolled = (window.pageYOffset > 16)"
         :class="scrolled ? 'bg-slate-950/90 backdrop-blur-md border-b border-slate-800/80' : 'bg-transparent'"
         class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group" aria-label="{{ $siteName }} - Home">
                    <span class="w-9 h-9 rounded-lg bg-blue-500/90 flex items-center justify-center text-slate-900 font-bold text-sm font-mono group-hover:bg-blue-400 transition">IDN</span>
                    <span class="font-semibold text-lg text-white tracking-tight">{{ $siteName }}</span>
                </a>

                <div class="hidden md:flex items-center gap-6">
                    @php
                        $menu = \App\Models\Menu::where('name', 'primary')->first();
                        $items = $menu ? $menu->items->load('children') : collect();
                    @endphp
                    @foreach($items as $item)
                        @php
                            $url = $item->url;
                            if (str_starts_with($url, '#') && !request()->is('/')) {
                                $url = url('/') . $url;
                            }
                        @endphp
                        <a href="{{ $url }}" class="text-sm font-medium text-slate-300 hover:text-white transition">{{ $item->title }}</a>
                    @endforeach

                    <livewire:language-switch />

                    <a href="mailto:ichsanworkthings@gmail.com" class="px-4 py-2 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-white text-sm font-medium border border-slate-700/80 transition">
                        {{ __('Contact Me') }}
                    </a>
                </div>

                <button x-on:click="open = !open" class="md:hidden p-2 text-slate-400 hover:text-white rounded-lg" aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak class="md:hidden bg-slate-900/95 border-t border-slate-800">
            <div class="px-4 py-4 space-y-2">
                @php $menu = \App\Models\Menu::where('name', 'primary')->first(); $items = $menu ? $menu->items : collect(); @endphp
                @foreach($items as $item)
                    @php $url = str_starts_with($item->url, '#') && !request()->is('/') ? url('/') . $item->url : $item->url; @endphp
                    <a href="{{ $url }}" class="block px-4 py-3 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 transition">{{ $item->title }}</a>
                @endforeach
                <div class="pt-2 border-t border-slate-800">
                    <livewire:language-switch />
                </div>
                <a href="mailto:ichsanworkthings@gmail.com" class="block px-4 py-3 rounded-lg bg-slate-800 text-white font-medium">{{ __('Contact Me') }}</a>
            </div>
        </div>
    </nav>

    <main id="main" class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-slate-900/50 border-t border-slate-800/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-center md:text-left">
                    <span class="font-semibold text-white">{{ $siteName }}</span>
                    <span class="text-slate-500 mx-2">·</span>
                    <span class="text-slate-500 text-sm">{{ __('Architect & AI Specialist') }}</span>
                </div>
                <div class="flex items-center gap-6">
                    <livewire:language-switch />
                    <a href="mailto:ichsanworkthings@gmail.com" class="text-slate-400 hover:text-blue-400 text-sm transition">{{ __('Contact Me') }}</a>
                    <a href="https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-blue-400 text-sm transition" aria-label="LinkedIn">LinkedIn</a>
                </div>
            </div>
            <p class="mt-8 pt-6 border-t border-slate-800 text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} {{ $siteName }}. {{ __('Footer Rights') }}
            </p>
        </div>
    </footer>
</body>
</html>
