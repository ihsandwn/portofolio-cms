<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-surface text-on-background font-body antialiased selection:bg-primary-container selection:text-on-primary-container" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden bg-surface">

        <div x-show="sidebarOpen"
             x-on:click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-blueprint"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-blueprint"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-inverse-surface/40 z-20 md:hidden backdrop-blur-sm"></div>

        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             class="fixed z-30 inset-y-0 left-0 w-64 transition duration-blueprint ease-out transform bg-surface-container-lowest border-r border-outline-variant/20 md:translate-x-0 md:static md:inset-0 flex flex-col">
            <div class="h-20 flex items-center justify-center border-b border-outline-variant/20 px-4">
                <a href="/" wire:navigate class="flex items-center gap-2 group shrink-0">
                    <div class="w-8 h-8 bg-primary flex items-center justify-center text-on-primary font-bold font-label text-sm group-hover:bg-primary-dim transition-colors duration-blueprint">
                        P
                    </div>
                    <span class="text-lg font-headline font-bold tracking-tight text-on-background">CMS<span class="text-primary">Portofolio</span></span>
                </a>
            </div>

            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto">
                <p class="px-3 font-label text-[10px] font-semibold text-primary uppercase tracking-[0.2em] mb-2">{{ __('Main Module') }}</p>

                @can('view_dashboard')
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>
                @endcan

                <p class="px-3 font-label text-[10px] font-semibold text-primary uppercase tracking-[0.2em] mt-6 mb-2">{{ __('Content System') }}</p>

                @can('view_portfolios')
                <a href="{{ route('admin.portfolios.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.portfolios.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="font-medium text-sm">Portfolios</span>
                </a>
                @endcan

                @can('view_services')
                <a href="{{ route('admin.services.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.services.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    <span class="font-medium text-sm">Services</span>
                </a>
                @endcan

                @can('view_settings')
                <a href="{{ route('admin.settings.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                     <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium text-sm">Settings</span>
                </a>
                @endcan

                @can('view_pages')
                <a href="{{ route('admin.pages.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.pages.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span class="font-medium text-sm">Pages</span>
                </a>
                @endcan

                @can('view_menus')
                <a href="{{ route('admin.menus.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.menus.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span class="font-medium text-sm">Menus</span>
                </a>
                @endcan

                @can('view_blogs')
                <a href="{{ route('admin.blog.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.blog.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                     <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <span class="font-medium text-sm">Blog Posts</span>
                </a>
                @endcan

                <p class="px-3 font-label text-[10px] font-semibold text-primary uppercase tracking-[0.2em] mt-6 mb-2">{{ __('Access Control') }}</p>

                @can('view_users')
                <a href="{{ route('admin.users.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.users.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium text-sm">Users</span>
                </a>
                @endcan

                @can('view_roles')
                <a href="{{ route('admin.roles.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.roles.*') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                     <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="font-medium text-sm">Roles</span>
                </a>
                @endcan

                @can('view_permissions')
                <a href="{{ route('admin.permissions.index') }}" wire:navigate class="flex items-center px-3 py-2.5 transition-colors duration-blueprint group {{ request()->routeIs('admin.permissions.index') ? 'bg-primary text-on-primary' : 'text-secondary hover:bg-surface-container-low hover:text-on-background' }}">
                    <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span class="font-medium text-sm">Permissions</span>
                </a>
                @endcan
            </nav>

            <div class="border-t border-outline-variant/20 p-4 bg-surface-container-low">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-container flex items-center justify-center text-on-primary-container font-bold font-label border border-outline-variant/20">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden min-w-0">
                        <p class="text-sm font-medium text-on-background truncate">{{ auth()->user()->name }}</p>
                        <p class="font-label text-[10px] text-primary uppercase tracking-wider font-semibold">{{ __('System Admin') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden relative">
            <header class="h-20 flex items-center justify-between px-6 md:px-8 bg-surface-container-lowest/80 backdrop-blur-md border-b border-outline-variant/20 sticky top-0 z-40">
                <div class="flex items-center gap-2">
                    <button type="button" x-on:click="sidebarOpen = !sidebarOpen" class="text-on-surface-variant hover:text-on-background md:hidden transition-colors duration-blueprint p-1" aria-label="{{ __('Toggle sidebar') }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl md:text-2xl font-headline font-bold text-on-background tracking-tight">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center gap-6">
                    <div class="mr-2">
                        <livewire:language-switch />
                    </div>

                    <div class="hidden md:flex items-center gap-2 bg-surface-container-low px-3 py-1.5 border border-outline-variant/20">
                         <div class="w-2 h-2 bg-primary animate-pulse"></div>
                         <span class="font-label text-[10px] uppercase tracking-wider text-secondary">{{ __('System Online') }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-primary hover:text-primary-dim transition-colors duration-blueprint text-sm font-medium flex items-center gap-2 group">
                             <span class="hidden md:inline font-label text-[10px] uppercase tracking-wider">{{ __('Sign Out') }}</span>
                             <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform duration-blueprint" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 relative z-10 bg-surface">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
