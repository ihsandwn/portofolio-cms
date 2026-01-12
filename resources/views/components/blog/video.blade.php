@props(['url'])

@php
    // Simple logic to convert YouTube watch URL to embed
    // Improve regex for robustness as needed
    $embedUrl = $url;
    if (str_contains($url, 'youtube.com/watch?v=')) {
        $videoId = explode('v=', $url)[1];
        $videoId = explode('&', $videoId)[0]; // Remove extra params
        $embedUrl = "https://www.youtube.com/embed/" . $videoId;
    } elseif (str_contains($url, 'youtu.be/')) {
        $videoId = explode('youtu.be/', $url)[1];
        $embedUrl = "https://www.youtube.com/embed/" . $videoId;
    }
    // Add Vimeo logic here if needed
@endphp

<div class="blog-video mb-8">
    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-lg bg-black">
        <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
    </div>
</div>
