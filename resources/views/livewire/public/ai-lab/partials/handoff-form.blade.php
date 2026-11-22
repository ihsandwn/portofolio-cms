{{-- Secure handoff: credential travels in a POST body, never in a URL.
     Parent agent: replace the <a href="{{ $generatedUrl }}"> ... </a> block in
     resources/views/livewire/public/ai-lab/show.blade.php with @include('livewire.public.ai-lab.partials.handoff-form'). --}}
@if($accessRequested && $token)
    <form method="POST" action="{{ $generatedUrl }}" target="_blank" rel="noopener" class="w-full">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <button type="submit" class="block w-full px-4 py-2 bg-primary text-on-primary font-label text-[10px] tracking-widest uppercase hover:bg-primary-dim transition-colors duration-blueprint">
            {{ __('Continue to App') }} &rarr;
        </button>
    </form>
@endif
