<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Panel' }} - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#050b14] text-blue-100 font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden bg-[#050b14]">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen" x-on:click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-blue-900/80 z-20 md:hidden backdrop-blur-sm"></div>

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-[#0a101f] border-r border-blue-900/30 md:translate-x-0 md:static md:inset-0 flex flex-col shadow-2xl">
            <!-- Logo -->
            <div class="h-20 flex items-center justify-center border-b border-blue-900/30 relative overflow-hidden">
                <!-- Electric Glow -->
                <div class="absolute inset-0 bg-blue-500/5 blur-xl"></div>
                
                <a href="/" class="relative z-10 flex items-center space-x-2 group">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-500/30 group-hover:scale-110 transition duration-300">
                        P
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">CMS<span class="text-blue-500">Portofolio</span></span>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-blue-500/80 uppercase tracking-widest mb-2 font-mono">Main Module</p>
                
                @can('view_dashboard')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>
                @endcan

                <p class="px-4 text-xs font-semibold text-blue-500/80 uppercase tracking-widest mt-6 mb-2 font-mono">Content System</p>

                @can('view_portfolios')
                <a href="{{ route('admin.portfolios.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.portfolios.*') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="font-medium">Portfolios</span>
                </a>
                @endcan
                
                @can('view_services')
                <a href="{{ route('admin.services.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.services.*') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    <span class="font-medium">Services</span>
                </a>
                @endcan

                @can('view_settings')
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                     <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium">Settings</span>
                </a>
                @endcan

                @can('view_menus')
                <a href="{{ route('admin.menus.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.menus.*') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span class="font-medium">Menus</span>
                </a>
                @endcan


                
                <a href="#" class="flex items-center px-4 py-3 rounded-xl transition-colors duration-200 text-blue-300/40 hover:bg-blue-900/10 hover:text-blue-300 opacity-60 cursor-not-allowed" title="Coming Soon">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    <span class="font-medium">Blogs Module</span>
                </a>

                <p class="px-4 text-xs font-semibold text-blue-500/80 uppercase tracking-widest mt-6 mb-2 font-mono">Access Control</p>

                @can('view_users')
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Users</span>
                </a>
                @endcan

                @can('view_roles')
                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.roles.*') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                     <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="font-medium">Roles</span>
                </a>
                @endcan

                @can('view_permissions')
                <a href="{{ route('admin.permissions.index') }}" class="flex items-center px-4 py-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.permissions.index') ? 'bg-blue-600 text-white shadow-[#2563eb66] shadow-lg' : 'text-blue-300/60 hover:bg-blue-900/20 hover:text-blue-200' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    <span class="font-medium">Permissions</span>
                </a>
                @endcan
            </nav>

            <!-- User Profile (Data) -->
            <div class="border-t border-blue-900/30 p-4 bg-[#080d1a]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600/20 flex items-center justify-center text-blue-400 font-bold border border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.2)]">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium text-blue-100 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-blue-400 uppercase tracking-wider font-bold">System Admin</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            
             <!-- Background Glow Effect -->
             <div class="absolute top-0 left-0 w-full h-96 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none z-0"></div>

            <!-- Top Header -->
            <header class="h-20 flex items-center justify-between px-6 md:px-8 bg-[#0a101f]/80 backdrop-blur-md border-b border-blue-900/30 sticky top-0 z-40">
                <div class="flex items-center">
                    <button x-on:click="sidebarOpen = !sidebarOpen" class="text-blue-400 focus:outline-none md:hidden hover:text-white transition">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl md:text-2xl font-bold text-white tracking-tight ml-2 md:ml-0">{{ $title ?? 'Dashboard' }}</h1>
                </div>

                <div class="flex items-center space-x-6">
                    <!-- Language Switcher -->
                    <div class="mr-4">
                        <livewire:language-switch />
                    </div>

                    <!-- Status Indicator -->
                    <div class="hidden md:flex items-center space-x-2 bg-blue-900/20 px-3 py-1.5 rounded-full border border-blue-500/20">
                         <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.6)]"></div>
                         <span class="text-xs font-semibold text-blue-200">System Online</span>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-blue-400 hover:text-white transition-all duration-200 text-sm font-medium flex items-center group">
                             <span class="mr-2 hidden md:inline group-hover:tracking-wide transition-all">Sign Out</span>
                             <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 relative z-10 scrollbar-thin scrollbar-thumb-blue-900/50 scrollbar-track-transparent">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
