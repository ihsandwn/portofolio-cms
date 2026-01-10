<div>
    <div class="flex justify-between items-center mb-6">
        <div class="relative w-64">
             <input wire:model.live="search" type="text" placeholder="Search..." class="w-full bg-slate-800 text-slate-100 border border-slate-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <a href="{{ route('admin.portfolios.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg transition shadow-lg shadow-indigo-500/20">
            + New Project
        </a>
    </div>

    <div class="bg-slate-800 rounded-lg border border-slate-700 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-400">
            <thead class="bg-slate-900/50 text-slate-200 uppercase font-medium">
                <tr>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Client</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Featured</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @forelse($portfolios as $portfolio)
                <tr class="hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4 font-medium text-slate-100">
                        {{ $portfolio->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $portfolio->client ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold 
                            {{ $portfolio->type === 'ai_agent' ? 'bg-purple-500/20 text-purple-300' : 'bg-cyan-500/20 text-cyan-300' }}">
                            {{ ucfirst(str_replace('_', ' ', $portfolio->type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($portfolio->is_featured)
                            <span class="text-emerald-400">Yes</span>
                        @else
                            <span class="text-slate-600">No</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex space-x-3">
                        <a href="{{ route('admin.portfolios.edit', $portfolio) }}" class="text-slate-300 hover:text-indigo-400">Edit</a>
                        <button wire:confirm="Are you sure?" wire:click="delete({{ $portfolio->id }})" class="text-rose-400 hover:text-rose-300">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        No portfolios found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $portfolios->links() }}
    </div>
</div>
