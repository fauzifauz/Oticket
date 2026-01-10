<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('Service Level Agreements') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Response & resolution targets</p>
            </div>
            <a href="{{ route('admin.slas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add SLA Rule
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 sm:px-8 pt-5 sm:pt-6 border-b border-gray-100 bg-white">
                    <div class="flex items-center gap-4 pb-4">
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 leading-tight">Priority Configurations</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Total {{ $slas->count() }} rules defined</p>
                        </div>
                    </div>
                </div>

                <div class="hidden md:block overflow-x-auto text-left">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center w-16">No</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Priority</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Response Target</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Resolution Target</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center w-24">Color</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($slas as $index => $sla)
                                <tr class="hover:bg-gray-50/70 transition-colors group">
                                    @php
                                        $color = $colors[$sla->priority] ?? 'gray';
                                    @endphp
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-bold text-gray-500">{{ $index + 1 }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-gray-700">{{ $sla->priority }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 text-xs font-mono font-medium text-gray-600 border border-gray-200">
                                            {{ $sla->response_time_minutes }}m
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-gray-50 text-xs font-mono font-medium text-gray-600 border border-gray-200">
                                            {{ $sla->resolution_time_minutes }}m
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            <div class="w-6 h-6 rounded-full bg-{{ $color }}-500 shadow-sm border-2 border-white ring-1 ring-gray-100" title="{{ ucfirst($color) }}"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.slas.edit', $sla->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.slas.destroy', $sla->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this rule?');">
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
                                        No SLA rules defined.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4 p-4">
                    @forelse($slas as $index => $sla)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3 text-left">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                     @php
                                        $color = $colors[$sla->priority] ?? 'gray';
                                    @endphp
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[9px] font-bold">{{ $index + 1 }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $sla->priority }} Priority</span>
                                </div>
                                <div class="w-4 h-4 rounded-full bg-{{ $color }}-500 shadow-sm"></div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 text-center">
                                <div class="bg-gray-50 rounded-lg p-2">
                                    <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Response</p>
                                    <p class="text-sm font-mono font-bold text-gray-700">{{ $sla->response_time_minutes }}m</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2">
                                    <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold">Resolution</p>
                                    <p class="text-sm font-mono font-bold text-gray-700">{{ $sla->resolution_time_minutes }}m</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end pt-2 border-t border-gray-50">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.slas.edit', $sla->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Edit</a>
                                    <form action="{{ route('admin.slas.destroy', $sla->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
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
                            No SLA rules defined.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
