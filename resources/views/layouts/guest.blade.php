<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} — Admin</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', system-ui, sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-slate-950 text-slate-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-12">
            <a href="/" wire:navigate class="mb-8 flex items-center gap-2 text-slate-400 hover:text-white transition">
                <span class="font-mono text-sm">&larr; Back to site</span>
            </a>
            <div class="w-full sm:max-w-md">
                <div class="bg-slate-900/80 backdrop-blur border border-slate-800 rounded-xl p-8 shadow-xl">
                    {{ $slot }}
                </div>
                <p class="mt-6 text-center text-xs text-slate-500">Secure admin access · CMS Portfolio</p>
            </div>
        </div>
    </body>
</html>
