<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            {{ $post ? 'Edit Post' : 'Create New Post' }}
        </h2>
        <div class="flex space-x-2">
            <button wire:click="save" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <span wire:loading.remove wire:target="save">Save Post</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Info -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Title</label>
                    <input type="text" wire:model.live="title" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Slug</label>
                    <input type="text" wire:model="slug" class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Content Builder -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Content Blocks</h3>
                
                <div class="space-y-4 mb-6" wire:sortable="updateBlockOrder">
                    @foreach($content_blocks as $index => $block)
                        <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 relative" wire:key="block-{{ $index }}">
                            <div class="flex justify-between items-center mb-2">
                                <span class="uppercase text-xs font-bold text-gray-500">{{ $block['type'] }}</span>
                                <div class="flex items-center space-x-2">
                                    <button wire:click="moveBlockUp({{ $index }})" class="text-gray-500 hover:text-blue-500">↑</button>
                                    <button wire:click="moveBlockDown({{ $index }})" class="text-gray-500 hover:text-blue-500">↓</button>
                                    <button wire:click="removeBlock({{ $index }})" class="text-red-500 hover:text-red-700">×</button>
                                </div>
                            </div>

                            <!-- Text Block -->
                            @if($block['type'] === 'text')
                                <div>
                                    <!-- Simple Text Area for now, should ideally be WYSIWYG -->
                                    <textarea wire:model="content_blocks.{{ $index }}.data" rows="5" class="w-full border rounded p-2 dark:bg-gray-800 dark:text-white" placeholder="Write content..."></textarea>
                                </div>
                            @endif

                            <!-- Video Block -->
                            @if($block['type'] === 'video')
                                <div>
                                    <label class="block text-sm mb-1">YouTube/Vimeo Embed URL</label>
                                    <input type="url" wire:model="content_blocks.{{ $index }}.url" class="w-full border rounded p-2 dark:bg-gray-800 dark:text-white" placeholder="https://youtube.com/...">
                                     @error("content_blocks.{$index}.url") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            
                            <!-- Gallery Block -->
                             @if($block['type'] === 'gallery')
                                <div>
                                    <label class="block text-sm mb-1">Images</label>
                                    <input type="file" wire:model="content_blocks.{{ $index }}.images" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    
                                     @if(isset($block['images']) && is_array($block['images']))
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            @foreach($block['images'] as $img)
                                                 @if(is_object($img))
                                                    <img src="{{ $img->temporaryUrl() }}" class="h-20 w-20 object-cover rounded">
                                                @elseif(is_string($img))
                                                    <img src="{{ asset('storage/' . $img) }}" class="h-20 w-20 object-cover rounded">
                                                @endif
                                            @endforeach
                                        </div>
                                     @endif
                                </div>
                            @endif

                             <!-- PDF Block -->
                             @if($block['type'] === 'pdf')
                                <div>
                                    <label class="block text-sm mb-1">PDF Title</label>
                                    <input type="text" wire:model="content_blocks.{{ $index }}.title" class="w-full border rounded p-2 mb-2 dark:bg-gray-800 dark:text-white" placeholder="E-Book Title">
                                    
                                    <label class="block text-sm mb-1">PDF File</label>
                                    <input type="file" wire:model="content_blocks.{{ $index }}.file" accept="application/pdf" class="block w-full text-sm">
                                    
                                    @if(isset($block['file']) && is_string($block['file']))
                                        <div class="mt-1 text-sm text-green-600">Current file: {{ basename($block['file']) }}</div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

                <!-- Add Block Buttons -->
                <div class="flex space-x-2 overflow-x-auto pb-2">
                    <button wire:click="addBlock('text')" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 rounded hover:bg-gray-300 text-sm">+ Text</button>
                    <button wire:click="addBlock('gallery')" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 rounded hover:bg-gray-300 text-sm">+ Gallery</button>
                    <button wire:click="addBlock('video')" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 rounded hover:bg-gray-300 text-sm">+ Video</button>
                    <button wire:click="addBlock('pdf')" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 rounded hover:bg-gray-300 text-sm">+ PDF</button>
                </div>
            </div>
            
            <!-- SEO Settings -->
             <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">SEO Settings</h3>
                <div class="grid grid-cols-1 gap-4">
                     <div>
                        <label class="block text-sm mb-1">Meta Title</label>
                        <input type="text" wire:model="seo_meta.title" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                    </div>
                     <div>
                        <label class="block text-sm mb-1">Meta Description</label>
                        <textarea wire:model="seo_meta.description" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar (Sidebar) -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Publishing</h3>
                
                <div class="mb-4">
                    <label class="block text-sm mb-1">Published At</label>
                    <input type="datetime-local" wire:model="published_at" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                </div>
                
                <div class="mb-4">
                     <label class="block text-sm mb-1">Category ID (Optional)</label>
                      <input type="number" wire:model="category_id" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white">
                </div>
                
                 <div class="mb-4">
                    <label class="block text-sm mb-1">Excerpt</label>
                    <textarea wire:model="excerpt" rows="3" class="w-full border rounded p-2 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
