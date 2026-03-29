<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Admin</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-surface text-on-background font-body selection:bg-primary-container selection:text-on-primary-container">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-12 blueprint-grid">
            <a href="/" wire:navigate class="mb-8 flex items-center gap-2 text-secondary hover:text-primary font-label text-[10px] uppercase tracking-widest transition-colors duration-blueprint">
                <span>&larr; {{ __('Back to site') }}</span>
            </a>
            <div class="w-full sm:max-w-md relative z-10">
                <div class="bg-surface-container-lowest border border-outline-variant/20 p-8 shadow-lg">
                    {{ $slot }}
                </div>
                <p class="mt-6 text-center font-label text-[10px] uppercase tracking-wider text-secondary">{{ __('Secure admin access · CMS Portfolio') }}</p>
            </div>
        </div>
    </body>
</html>
