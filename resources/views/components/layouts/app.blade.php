<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Ichsan Dwi Nugraha' }} - Senior Architect & AI Specialist</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Outfit', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased selection:bg-cyan-500/30 selection:text-cyan-200">
    
    <div class="fixed inset-0 z-[-1] bg-slate-950 bg-grid pointer-events-none"></div>
    <div class="fixed top-0 left-0 w-full h-full z-[-1] bg-gradient-to-b from-indigo-900/10 to-transparent pointer-events-none"></div>

    <!-- Navigation -->
    <nav x-data="{ open: false, scrolled: false }" 
         x-on:scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="scrolled ? 'bg-slate-900/80 backdrop-blur-md border-b border-white/5' : 'bg-transparent'"
         class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="/" class="flex items-center space-x-2 group">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-cyan-400 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition duration-300">
                        I
                    </div>
                    <span class="font-bold text-xl tracking-tight text-white">Ichsan<span class="text-cyan-400">.Dev</span></span>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="#about" class="text-sm font-medium text-slate-300 hover:text-white transition">About</a>
                    <a href="#services" class="text-sm font-medium text-slate-300 hover:text-white transition">Services</a>
                    <a href="#portfolio" class="text-sm font-medium text-slate-300 hover:text-white transition">Portfolio</a>
                    <a href="#ai-lab" class="text-sm font-medium text-cyan-400 hover:text-cyan-300 transition flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        AI Lab
                    </a>
                </div>

                <div class="hidden md:block">
                    <a href="mailto:ichsanworkthings@gmail.com" class="px-5 py-2.5 rounded-full bg-slate-800 hover:bg-slate-700 text-white text-sm font-medium border border-slate-700 transition hover:border-slate-500 hover:shadow-lg">
                        Contact Me
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button x-on:click="open = !open" class="text-slate-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" class="md:hidden bg-slate-900 border-b border-slate-800">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#about" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">About</a>
                <a href="#services" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">Services</a>
                <a href="#portfolio" class="block px-3 py-2 rounded-md text-base font-medium text-slate-300 hover:text-white hover:bg-slate-800">Portfolio</a>
                <a href="#ai-lab" class="block px-3 py-2 rounded-md text-base font-medium text-cyan-400 hover:text-cyan-300 hover:bg-slate-800">AI Lab</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mt-12 pt-8 border-t border-slate-800 text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} Ichsan Dwi Nugraha. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
