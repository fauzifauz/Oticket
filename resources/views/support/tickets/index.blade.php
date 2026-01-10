<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-left">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('My Assigned Tickets') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Manage and resolve your active support caseload</p>
            </div>
            <div>
                <a href="{{ route('support.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl font-bold text-[10px] uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition duration-150">
                    Dashboard Overview
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Filter Bar -->
                <div class="px-8 py-6 border-b border-gray-100 bg-white">
                    <form action="{{ route('support.tickets.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <div class="flex flex-col gap-1.5 text-left">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Filter Status</label>
                                <div class="flex gap-2">
                                    <a href="{{ route('support.tickets.index') }}" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ !request('status') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-gray-50 text-gray-500 hover:bg-gray-100' }}">
                                        All
                                    </a>
                                    @foreach($statuses as $status)
                                        <a href="{{ route('support.tickets.index', ['status' => $status->slug]) }}" 
                                           class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ request('status') == $status->slug ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-gray-50 text-gray-500 hover:bg-gray-100' }}">
                                            {{ $status->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 pb-0.5">
                            <a href="{{ route('support.tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:text-gray-600 hover:bg-gray-50 transition duration-150">
                                Reset Filter
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-center">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-left">Ticket</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-left">Reported By</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Priority</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Created</th>
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 uppercase tracking-tight">
                            @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50/70 transition-all duration-200 group text-[11px] font-bold text-gray-700">
                                <td class="px-8 py-5 text-left">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors truncate max-w-[250px]">{{ $ticket->subject }}</p>
                                        <p class="text-[9px] text-gray-400 tracking-widest">#{{ strtoupper(substr($ticket->uuid, 0, 8)) }} &bull; {{ $ticket->category->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-left">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="text-gray-900 truncate max-w-[120px]">{{ $ticket->user->name }}</p>
                                        <p class="text-[9px] text-gray-400 tracking-widest">{{ $ticket->user->department ?? 'General' }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex justify-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter shadow-sm
                                            {{ $ticket->priority == 'critical' ? 'bg-rose-500 text-white shadow-rose-100' : 
                                               ($ticket->priority == 'high' ? 'bg-amber-500 text-white shadow-amber-100' : 
                                               ($ticket->priority == 'normal' ? 'bg-indigo-500 text-white shadow-indigo-100' : 'bg-emerald-500 text-white shadow-emerald-100')) }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex justify-center">
                                        <span class="flex items-center gap-1.5 px-3 py-1 bg-gray-50 text-gray-700 rounded-lg text-[9px] font-black uppercase tracking-tighter border border-gray-100 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $ticket->status == 'open' ? 'bg-rose-500' : ($ticket->status == 'in_progress' ? 'bg-indigo-500' : 'bg-emerald-500') }}"></span>
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-gray-400 font-medium text-[10px]">
                                    {{ $ticket->created_at->format('d M Y') }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('support.tickets.show', $ticket->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white shadow-sm transition-all duration-200">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-200">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No support tickets match your criteria</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                    {{ $tickets->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
