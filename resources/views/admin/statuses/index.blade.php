<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('Ticket Statuses') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Workflow stages configuration</p>
            </div>
            <a href="{{ route('admin.statuses.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Status
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 sm:px-8 pt-5 sm:pt-6 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-4 pb-4">
                        <div class="p-2 bg-pink-50 text-pink-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">Workflow Sequence</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Total {{ $statuses->count() }} configured stages</p>
                        </div>
                    </div>
                </div>

                <div class="hidden md:block overflow-x-auto text-left">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center w-16">Order</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Name</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Slug</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Color</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($statuses as $status)
                                <tr class="hover:bg-gray-50/70 transition-colors group">
                                    <td class="px-4 py-3 text-center text-xs font-black text-gray-300">{{ $status->order }}</td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $status->name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-mono text-gray-500">{{ $status->slug }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-{{ $status->color }}-500 border border-gray-100 shadow-sm"></span>
                                            <span class="text-xs font-medium text-gray-600 capitalize">{{ $status->color }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.statuses.edit', $status->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.statuses.destroy', $status->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this status?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        No statuses configured. Run migrations or create one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4 p-4">
                    @forelse($statuses as $status)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3 text-left">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center justify-center w-6 h-6 rounded bg-gray-100 text-[10px] font-black text-gray-400">{{ $status->order }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $status->name }}</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-{{ $status->color }}-50 text-{{ $status->color }}-600 font-bold text-[9px] uppercase tracking-widest ring-1 ring-inset ring-{{ $status->color }}-500/10">
                                    {{ $status->color }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                <code class="text-[10px] bg-gray-50 px-2 py-1 rounded text-gray-500 font-mono">{{ $status->slug }}</code>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.statuses.edit', $status->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Edit</a>
                                    <form action="{{ route('admin.statuses.destroy', $status->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-400 hover:text-rose-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-sm">
                            No statuses configured.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
