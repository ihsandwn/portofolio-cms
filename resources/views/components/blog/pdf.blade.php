@props(['file', 'title'])

<div class="blog-pdf mb-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    @if($title)
        <h4 class="font-bold text-gray-900 dark:text-white mb-2 flex items-center">
            <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            {{ $title }}
        </h4>
    @endif
    
    <div class="relative w-full h-96 rounded overflow-hidden border">
         <iframe src="{{ asset('storage/' . $file) }}" class="w-full h-full" title="{{ $title }}">
            <p>Your browser does not support PDFs. <a href="{{ asset('storage/' . $file) }}">Download the PDF</a>.</p>
        </iframe>
    </div>
    
    <div class="mt-2 text-right">
        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Download PDF</a>
    </div>
</div>
