<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-100">Pages</h1>
        <a href="{{ route('admin.pages.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition">Create Page</a>
    </div>

    <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="bg-slate-900 text-slate-200 uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @foreach($pages as $page)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 font-medium text-white">{{ $page->title['en'] }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $page->slug }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $page->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                {{ $page->is_active ? 'Active' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="text-indigo-400 hover:text-indigo-300 transition">Edit</a>
                            <button wire:click="delete({{ $page->id }})" wire:confirm="Are you sure you want to delete this page?" class="text-red-400 hover:text-red-300 transition">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
