<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('Ticket Categories') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Organize support topics</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Category
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 sm:px-8 pt-5 sm:pt-6 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-4 pb-4">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">Category List</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Total {{ $categories->count() }} active categories</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="hidden md:block overflow-x-auto text-left">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Created</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50/70 transition-colors group">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-500">
                                        {{ $category->id }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                            {{ $category->name }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 text-right">
                                        {{ $category->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                        No categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4 p-4">
                     @forelse($categories as $category)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-900">{{ $category->name }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                <span class="text-[10px] font-medium text-gray-400">{{ $category->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
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
                            No categories found.
                        </div>
                     @endforelse
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
