<x-app-layout>
    <x-slot name="header">
        <h2 class="font-headline font-semibold text-xl text-on-background leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-surface">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-surface-container-lowest border border-outline-variant/20 overflow-hidden">
                <div class="p-6 text-on-background">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
