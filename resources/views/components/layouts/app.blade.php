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
    $wireNavigate = function (string $url): bool {
        if (str_starts_with($url, '#')) {
            return false;
        }
        if (str_starts_with($url, 'mailto:') || str_starts_with($url, 'tel:')) {
            return false;
        }
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return true;
        }
        $base = rtrim(config('app.url'), '/');

        return str_starts_with($url, $base . '/') || $url === $base;
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES) !!}</script>
</head>
<body class="bg-surface text-on-background font-body antialiased selection:bg-primary-container selection:text-on-primary-container flex flex-col min-h-screen">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-primary focus:text-on-primary focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface">{{ __('Skip to content') }}</a>

    <div class="fixed inset-0 z-[-1] bg-surface blueprint-grid pointer-events-none opacity-60"></div>
    <div class="fixed top-0 left-6 h-full w-px bg-outline-variant/10 pointer-events-none z-0 hidden sm:block" aria-hidden="true"></div>
    <div class="fixed top-0 right-6 h-full w-px bg-outline-variant/10 pointer-events-none z-0 hidden sm:block" aria-hidden="true"></div>

    <nav x-data="{ open: false, scrolled: false }"
         x-on:scroll.window="scrolled = (window.pageYOffset > 16)"
         :class="scrolled ? 'bg-surface-container-lowest/80 backdrop-blur-md border-b border-outline-variant/30' : 'bg-transparent border-b border-transparent'"
         class="fixed w-full z-50 transition-all duration-blueprint border-b">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-16 items-center gap-4">
                <a href="{{ url('/') }}"
                   @if($wireNavigate(url('/'))) wire:navigate @endif
                   class="flex items-center gap-2.5 group shrink-0 min-w-0"
                   aria-label="{{ $siteName }} - Home">
                    <span class="w-8 h-8 shrink-0 bg-primary flex items-center justify-center text-on-primary font-bold text-[10px] font-label tracking-tighter group-hover:bg-primary-dim transition-colors duration-blueprint">IDN</span>
                    <span class="font-label text-sm font-bold tracking-tighter uppercase text-on-background truncate">{{ $siteName }}</span>
                </a>

                <div class="hidden md:flex items-center gap-8">
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
                            $useNavigate = $wireNavigate($url);
                        @endphp
                        <a href="{{ $url }}"
                           @if($useNavigate) wire:navigate @endif
                           class="font-headline text-[10px] uppercase tracking-[0.2em] font-medium text-secondary hover:text-on-background transition-colors duration-blueprint">{{ $item->title }}</a>
                    @endforeach

                    <livewire:language-switch />

                    <span class="text-outline hidden lg:inline-flex" aria-hidden="true">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </span>

                    <a href="mailto:ichsanworkthings@gmail.com"
                       class="px-4 py-2 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint shrink-0">
                        {{ __('Contact Me') }}
                    </a>
                </div>

                <button type="button" x-on:click="open = !open" class="md:hidden p-2 text-on-surface-variant hover:text-on-background transition-colors duration-blueprint" aria-label="{{ __('Toggle menu') }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <div x-show="open"
             x-cloak
             x-transition:enter="transition ease-out duration-blueprint"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-blueprint"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-1"
             class="md:hidden bg-surface-container-lowest border-t border-outline-variant/30">
            <div class="px-4 py-4 space-y-1">
                @php $menu = \App\Models\Menu::where('name', 'primary')->first(); $items = $menu ? $menu->items : collect(); @endphp
                @foreach($items as $item)
                    @php
                        $url = str_starts_with($item->url, '#') && !request()->is('/') ? url('/') . $item->url : $item->url;
                        $useNavigate = $wireNavigate($url);
                    @endphp
                    <a href="{{ $url }}"
                       @if($useNavigate) wire:navigate @endif
                       class="block px-4 py-3 font-headline text-xs uppercase tracking-widest text-secondary hover:text-on-background hover:bg-surface-container-low transition-colors duration-blueprint">{{ $item->title }}</a>
                @endforeach
                <div class="pt-3 mt-3 border-t border-outline-variant/20">
                    <livewire:language-switch />
                </div>
                <a href="mailto:ichsanworkthings@gmail.com" class="block px-4 py-3 mt-2 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase text-center">{{ __('Contact Me') }}</a>
            </div>
        </div>
    </nav>

    <main id="main" class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-surface-container-low border-t border-outline-variant/20">
        <div class="max-w-7xl mx-auto px-6 py-10 md:py-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="font-label text-[9px] tracking-widest uppercase text-outline text-center md:text-left leading-relaxed max-w-md">
                    &copy; {{ date('Y') }} {{ strtoupper($siteName) }} / {{ __('Architect & AI Specialist') }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-6 md:gap-8">
                    <livewire:language-switch />
                    <a href="mailto:ichsanworkthings@gmail.com" class="font-label text-[9px] tracking-widest uppercase text-outline hover:text-primary transition-colors duration-blueprint underline decoration-outline-variant/40 underline-offset-2">{{ __('Contact Me') }}</a>
                    <a href="https://www.linkedin.com/in/ichsan-dwi-nugraha-694a6a114" target="_blank" rel="noopener noreferrer" class="font-label text-[9px] tracking-widest uppercase text-outline hover:text-primary transition-colors duration-blueprint underline decoration-outline-variant/40 underline-offset-2" aria-label="LinkedIn">LinkedIn</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
