<div class="flex items-center space-x-2">
    <button wire:click="switch('en')" class="px-2 py-1 text-sm font-medium rounded {{ app()->getLocale() === 'en' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
        EN
    </button>
    <button wire:click="switch('id')" class="px-2 py-1 text-sm font-medium rounded {{ app()->getLocale() === 'id' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
        ID
    </button>
</div>
