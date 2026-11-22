<div class="max-w-6xl mx-auto pt-32 py-12 px-4 sm:px-6 lg:px-10">
    <nav class="flex mb-8 font-label text-[11px] uppercase tracking-wider text-secondary" aria-label="{{ __('Breadcrumb') }}">
        <ol class="inline-flex items-center flex-wrap gap-x-2 gap-y-1">
            <li><a href="/" wire:navigate class="hover:text-primary transition-colors duration-blueprint">{{ __('Home') }}</a></li>
            <li><span class="text-outline-variant">/</span></li>
            <li><a href="{{ route('ai-lab.index') }}" wire:navigate class="hover:text-primary transition-colors duration-blueprint">{{ __('AI Lab') }}</a></li>
            <li><span class="text-outline-variant">/</span></li>
            <li class="text-on-background font-headline font-medium truncate max-w-xs" aria-current="page">{{ $project->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <header class="mb-8">
                <div class="inline-block px-3 py-1 border border-outline-variant/30 bg-surface-container-low font-label text-[11px] uppercase tracking-[0.2em] text-primary mb-4">
                     {{ $project->slug }}
                </div>
                <h1 class="font-headline text-4xl md:text-5xl font-bold text-on-background mb-6">{{ $project->title }}</h1>
                <p class="text-xl text-secondary leading-relaxed">{{ $project->description }}</p>
            </header>

            <div class="bg-surface-container-low p-8 border border-outline-variant/20 mb-12">
                 <h2 class="font-headline text-2xl font-bold text-on-background mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 bg-primary-container text-on-primary-container font-label text-xs flex items-center justify-center shrink-0">01</span>
                    {{ __('Demonstration / Case Study') }}
                </h2>
                <div class="prose-blueprint">
                    @safeHtml(Str::markdown($project->case_study ?? ''))
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-surface-container-lowest p-6 border border-outline-variant/20">
                <h3 class="font-headline text-lg font-bold text-on-background mb-4">{{ __('Demo notes') }}</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-1">{{ __('Intended use') }}</dt>
                        <dd class="text-sm text-secondary">{{ __('AI Lab Intended Use') }}</dd>
                    </div>
                    <div>
                        <dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-1">{{ __('Input handling') }}</dt>
                        <dd class="text-sm text-secondary">{{ __('AI Lab Input Handling') }}</dd>
                    </div>
                    <div>
                        <dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-1">{{ __('Output handling') }}</dt>
                        <dd class="text-sm text-secondary">{{ __('AI Lab Output Handling') }}</dd>
                    </div>
                    <div>
                        <dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-1">{{ __('Limitations') }}</dt>
                        <dd class="text-sm text-secondary">{{ __('AI Lab Limitations') }}</dd>
                    </div>
                    <div>
                        <dt class="font-label text-[11px] uppercase tracking-widest text-primary mb-1">{{ __('Privacy & retention') }}</dt>
                        <dd class="text-sm text-secondary">{{ __('AI Lab Retention') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-surface-container-lowest p-6 border border-outline-variant/20">
                 <h3 class="font-headline text-lg font-bold text-on-background mb-4">{{ __('Tech Stack') }}</h3>
                 <div class="flex flex-wrap gap-2">
                    @foreach($project->tech_stack ?? [] as $tech)
                    <span class="px-3 py-1 bg-surface-container-high text-on-background font-label text-[11px] uppercase tracking-wider border border-outline-variant/20">{{ $tech }}</span>
                    @endforeach
                </div>
            </div>

             @if($project->repo_url || $project->url)
            <div class="bg-surface-container-low p-6 border border-primary/30">
                <h3 class="font-headline text-lg font-bold text-on-background mb-4">{{ __('Access') }}</h3>
                <div class="flex flex-col gap-3">
                    @if($project->url)
                        @if($accessRequested)
                            <div class="border border-primary-container bg-primary-container/30 p-4 text-center">
                                <h4 class="text-primary font-headline font-bold text-lg mb-2">{{ __('Access Granted!') }}</h4>
                                <p class="text-secondary text-sm mb-2">{{ __('Your secure session is ready.') }}</p>
                                <p class="text-xs text-secondary mb-4">{{ __('Access expires after 10 minutes.') }}</p>
                                @include('livewire.public.ai-lab.partials.handoff-form')
                            </div>
                        @else
                            <div class="space-y-3">
                                <div>
                                    <label for="demo-email" class="sr-only">{{ __('Email address') }}</label>
                                    <input
                                        id="demo-email"
                                        type="email"
                                        wire:model="email"
                                        placeholder="{{ __('Enter your email to access...') }}"
                                        class="w-full min-h-11 border-0 border-b border-outline bg-surface-container-lowest text-on-background placeholder:text-on-surface-variant focus:border-b-2 focus:border-primary focus:ring-0 px-0 py-2.5 transition-[border-width] duration-blueprint"
                                    >
                                    @error('email')
                                        <span class="text-error text-xs mt-1 block" role="alert" aria-live="polite">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button
                                    type="button"
                                    wire:click="requestAccess"
                                    wire:loading.attr="disabled"
                                    class="w-full min-h-11 flex items-center justify-center px-4 py-2.5 bg-primary text-on-primary font-label text-[11px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span wire:loading.remove wire:target="requestAccess">{{ __('Request Access') }}</span>
                                    <span wire:loading wire:target="requestAccess" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-on-primary" fill="none" viewBox="0 0 24 24" aria-hidden="true"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        {{ __('Verifying...') }}
                                    </span>
                                </button>
                                <p class="text-xs text-secondary text-center">
                                    {{ __('AI Lab Privacy Notice') }}
                                </p>
                            </div>
                        @endif
                    @endif

                    @if($project->repo_url)
                     <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="w-full min-h-11 text-center px-4 py-2 bg-surface-container-high hover:bg-surface-container text-on-background font-label text-[11px] tracking-widest uppercase border border-outline-variant/30 transition-colors duration-blueprint mt-2">
                        {{ __('View Code') }}
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
