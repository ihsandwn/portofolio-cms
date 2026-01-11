<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">Menu Builder: {{ str_replace('_', ' ', $menu->name) }}</h1>
            <a href="{{ route('admin.menus.index') }}" class="text-slate-400 hover:text-white transition text-sm">Back to Menus</a>
        </div>
        <button wire:click="create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition">Add Item</button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-500/10 text-green-400 p-4 rounded-lg border border-green-500/20 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
        <ul class="divide-y divide-slate-700">
            @foreach($items as $item)
                <li class="p-4 hover:bg-slate-700/30 transition flex items-center justify-between group">
                    <div>
                        <div class="font-medium text-white flex items-center">
                            {{ $item->title['en'] }} 
                            <span class="ml-2 text-xs text-slate-500 border border-slate-600 px-1 rounded">Order: {{ $item->order }}</span>
                        </div>
                        <div class="text-sm text-slate-400">{{ $item->url }}</div>
                         @if(isset($item->title['id']) && $item->title['id'])
                            <div class="text-xs text-slate-500 mt-1">ID: {{ $item->title['id'] }}</div>
                        @endif
                    </div>
                    <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition">
                        <button wire:click="edit({{ $item->id }})" class="text-indigo-400 hover:text-indigo-300">Edit</button>
                        <button wire:click="delete({{ $item->id }})" class="text-red-400 hover:text-red-300" wire:confirm="Delete this item?">Delete</button>
                    </div>
                </li>
            @endforeach
            
            @if($items->isEmpty())
                <li class="p-8 text-center text-slate-500">No menu items found.</li>
            @endif
        </ul>
    </div>

    <!-- Modal / Form Overlay -->
    <div x-show="$wire.showForm" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>
            </div>

            <div class="inline-block align-bottom bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-700">
                <form wire:submit="save">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-slate-100 mb-4" id="modal-title">
                            {{ $editingItem ? 'Edit Item' : 'Add Item' }}
                        </h3>
                        
                        <div class="space-y-4" x-data="{ lang: 'en' }">
                            <!-- Language Tabs -->
                            <div class="flex space-x-1 bg-slate-900 p-1 rounded-lg w-fit">
                                <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 text-xs font-medium rounded transition">EN</button>
                                <button type="button" @click="lang = 'id'" :class="lang === 'id' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:text-white'" class="px-3 py-1 text-xs font-medium rounded transition">ID</button>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1">Title</label>
                                <div x-show="lang === 'en'">
                                    <input type="text" wire:model="title.en" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="English Title">
                                    @error('title.en') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div x-show="lang === 'id'">
                                    <input type="text" wire:model="title.id" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Indonesia Title">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1">URL / Hash</label>
                                <input type="text" wire:model="url" class="w-full bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="#about or /path">
                                @error('url') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-1">Order</label>
                                <input type="number" wire:model="order" class="w-20 bg-slate-900 border border-slate-700 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" wire:click="$set('showForm', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-700 shadow-sm px-4 py-2 bg-slate-800 text-base font-medium text-slate-300 hover:text-white sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
