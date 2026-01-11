<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-100">Services</h1>
        <a href="{{ route('admin.services.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition">Add Service</a>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-500/10 text-green-400 p-4 rounded-lg border border-green-500/20">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="bg-slate-900 text-slate-200 uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Title (EN)</th>
                    <th class="px-6 py-4">Title (ID)</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @foreach($services as $service)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 font-medium text-white">{{ $service->title['en'] ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $service->title['id'] ?? '-' }}</td>
                        <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $service->category) }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-indigo-400 hover:text-indigo-300 transition">Edit</a>
                            <button wire:click="delete({{ $service->id }})" 
                                    wire:confirm="Are you sure you want to delete this service?"
                                    class="text-red-400 hover:text-red-300 transition">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
