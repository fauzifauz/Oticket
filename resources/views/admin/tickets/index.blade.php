<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('Ticket Registry') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Master incident repository</p>
            </div>
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full lg:w-auto mt-4 lg:mt-0">
                <!-- General Exports -->
                <div class="flex items-center h-11 sm:h-10 lg:h-9 bg-white border border-gray-300 rounded-xl lg:rounded-lg shadow-sm overflow-hidden shrink-0">
                    <a href="{{ route('admin.tickets.export-pdf', request()->query()) }}" class="flex-1 lg:flex-none h-full inline-flex items-center justify-center px-5 lg:px-3 border-r border-gray-200 font-bold text-[10px] sm:text-[11px] lg:text-[9px] text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition" title="Export General PDF">
                        <svg class="w-4 h-4 lg:w-3.5 lg:h-3.5 mr-2 lg:mr-1 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </a>
                    <a href="{{ route('admin.tickets.export', request()->query()) }}" class="flex-1 lg:flex-none h-full inline-flex items-center justify-center px-5 lg:px-3 font-bold text-[10px] sm:text-[11px] lg:text-[9px] text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition" title="Export General CSV">
                        <svg class="w-4 h-4 lg:w-3.5 lg:h-3.5 mr-2 lg:mr-1 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        CSV
                    </a>
                </div>

                <!-- Monthly Export -->
                <div class="flex flex-col sm:flex-row gap-2 lg:gap-0 w-full lg:w-auto">
                    <form action="{{ route('admin.tickets.export-monthly-pdf') }}" method="GET" id="monthlyExportForm" class="flex items-center h-11 sm:h-10 lg:h-9 bg-white border border-gray-300 rounded-xl lg:rounded-lg shadow-sm overflow-hidden flex-1 lg:flex-initial">
                        <input type="month" name="month" id="exportMonth" value="{{ date('Y-m') }}" 
                               class="h-full px-3 lg:px-2 py-0 border-0 rounded-l-xl lg:rounded-l-lg text-[10px] sm:text-[11px] lg:text-[10px] font-extrabold focus:ring-0 focus:outline-none bg-transparent leading-none text-gray-700 w-full sm:w-28 lg:w-24 border-r border-gray-200">
                        <div class="flex items-center h-full flex-1 lg:flex-none">
                            <button type="submit" class="flex-1 lg:flex-none h-full inline-flex items-center justify-center px-5 lg:px-3 bg-red-600 font-black text-[10px] sm:text-[11px] lg:text-[9px] text-white uppercase tracking-tighter hover:bg-red-700 transition" title="Download Monthly PDF">
                                PDF
                            </button>
                            <button type="button" 
                                    onclick="window.location.href='{{ route('admin.tickets.export-monthly-csv') }}?month=' + document.getElementById('exportMonth').value"
                                    class="flex-1 lg:flex-none h-full inline-flex items-center justify-center px-5 lg:px-3 bg-emerald-600 rounded-r-xl lg:rounded-r-lg font-black text-[10px] sm:text-[11px] lg:text-[9px] text-white uppercase tracking-tighter hover:bg-emerald-700 transition" title="Download Monthly CSV">
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
 
                <!-- Yearly Export -->
                <div x-data="{ open: false, year: '{{ date('Y') }}' }" class="relative flex flex-col sm:flex-row gap-2 lg:gap-0 w-full lg:w-auto">
                    <div class="flex items-center h-11 sm:h-10 lg:h-9 bg-white border border-gray-300 rounded-xl lg:rounded-lg shadow-sm font-bold text-[10px] uppercase tracking-widest overflow-hidden flex-1 lg:flex-initial">
                        <select x-model="year" class="h-full border-0 bg-transparent text-[10px] sm:text-[11px] lg:text-[10px] font-extrabold focus:ring-0 py-0 pl-4 lg:pl-3 pr-10 lg:pr-8 rounded-l-xl lg:rounded-l-lg text-gray-700 cursor-pointer appearance-none flex-1 lg:flex-none lg:w-20 border-r border-gray-200">
                            @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                        <button @click="open = !open" class="flex-1 lg:flex-none h-full px-6 lg:px-4 flex items-center justify-center gap-2 bg-slate-800 text-white rounded-r-xl lg:rounded-r-lg hover:bg-slate-900 transition-all whitespace-nowrap">
                            <span>Years</span>
                            <svg class="w-4 h-4 lg:w-3 lg:h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-2xl border border-gray-100 py-2 z-50">
                        <a :href="'{{ route('admin.tickets.export-yearly-pdf') }}?year=' + year" class="flex items-center px-4 py-2 hover:bg-gray-50 text-[10px] font-black text-gray-700 transition-colors uppercase tracking-widest">
                            <svg class="w-4 h-4 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export Yearly PDF
                        </a>
                        <a :href="'{{ route('admin.tickets.export-yearly-csv') }}?year=' + year" class="flex items-center px-4 py-2 hover:bg-gray-50 text-[10px] font-black text-gray-700 transition-colors uppercase tracking-widest">
                            <svg class="w-4 h-4 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Yearly CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats -->
            <!-- Stats -->
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 sm:gap-4">
                <div class="bg-white p-2 sm:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-1 sm:gap-2 aspect-[4/3] sm:aspect-auto transition-all hover:shadow-md">
                    <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Total</p>
                    <p class="text-xl sm:text-2xl font-black text-gray-900 leading-none">{{ $totalTickets }}</p>
                </div>

                @foreach($statuses as $status)
                    <div class="bg-white p-2 sm:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center gap-1 sm:gap-2 aspect-[4/3] sm:aspect-auto transition-all hover:shadow-md">
                        <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none truncate w-full">{{ $status->name }}</p>
                        <p class="text-xl sm:text-2xl font-black text-{{ $status->color }}-600 leading-none">{{ $status->tickets_count }}</p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/30">
                    <form action="{{ route('admin.tickets.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        <div class="col-span-2 md:col-span-1 relative group">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, subject, user..." 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[11px] sm:text-xs font-bold focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none placeholder:text-gray-400 uppercase tracking-tight">
                        </div>
                        
                        <div class="col-span-1">
                            <select name="status" class="w-full py-2.5 px-3 bg-white border border-gray-100 rounded-xl text-[11px] sm:text-xs font-bold text-gray-600 focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none cursor-pointer appearance-none uppercase tracking-tight" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->slug }}" {{ request('status') == $status->slug ? 'selected' : '' }}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-1">
                            <select name="priority" class="w-full py-2.5 px-3 bg-white border border-gray-100 rounded-xl text-[11px] sm:text-xs font-bold text-gray-600 focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none cursor-pointer appearance-none uppercase tracking-tight" onchange="this.form.submit()">
                                <option value="">All Priorities</option>
                                @foreach($slaRules->pluck('priority')->unique() as $p)
                                    <option value="{{ $p }}" {{ request('priority') == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-1">
                            <select name="assigned" class="w-full py-2.5 px-3 bg-white border border-gray-100 rounded-xl text-[11px] sm:text-xs font-bold text-gray-600 focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none cursor-pointer appearance-none uppercase tracking-tight" onchange="this.form.submit()">
                                <option value="">All Assignments</option>
                                <option value="1" {{ request('assigned') === '1' ? 'selected' : '' }}>Assigned</option>
                                <option value="0" {{ request('assigned') === '0' ? 'selected' : '' }}>Unassigned</option>
                            </select>
                        </div>

                        <div class="col-span-1 lg:col-span-1 flex items-center gap-2">
                             <a href="{{ route('admin.tickets.index') }}" class="px-3 py-2.5 bg-white hover:bg-gray-50 text-gray-400 rounded-xl text-[10px] sm:text-xs font-bold transition flex-1 text-center border border-gray-100 uppercase tracking-widest shadow-sm">Clear</a>
                            <button type="submit" class="flex-[1.5] px-3 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-widest transition shadow-lg shadow-gray-200 active:scale-95">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100 break-words">
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center w-24">ID</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest min-w-[300px]">Subject</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Requester</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Priority</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Assigned To</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Created</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50/70 transition-colors group">
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-block px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-mono font-bold hover:bg-indigo-100 hover:text-indigo-600 transition">
                                            #{{ $ticket->id }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="block">
                                            <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $ticket->subject }}</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $ticket->category->name }}</span>
                                                @if($ticket->sla_due_at && $ticket->sla_due_at < now() && !in_array($ticket->ticketStatus->slug, ['resolved', 'closed']))
                                                    <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest animate-pulse">• SLA Overdue</span>
                                                @endif
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 text-[9px] font-black uppercase">
                                                {{ substr($ticket->user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-gray-900">{{ $ticket->user->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @php
                                            $pColor = $priorityColors[$ticket->priority] ?? 'gray';
                                            $colorClasses = match($pColor) {
                                                'rose' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                                'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                default => 'bg-gray-50 text-gray-600 border-gray-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg border text-[9px] font-black uppercase tracking-widest {{ $colorClasses }}">
                                            {{ $ticket->priority }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-{{ $ticket->ticketStatus->color }}-50 text-{{ $ticket->ticketStatus->color }}-600 font-bold text-[9px] uppercase tracking-widest ring-1 ring-inset ring-{{ $ticket->ticketStatus->color }}-500/10">
                                            {{ $ticket->ticketStatus->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($ticket->support)
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-[8px] font-black uppercase border-2 border-white shadow-sm">
                                                    {{ substr($ticket->support->name, 0, 2) }}
                                                </div>
                                                <span class="text-xs font-medium text-gray-600">{{ $ticket->support->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="text-xs font-medium text-gray-500" title="{{ $ticket->created_at }}">
                                            {{ $ticket->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            <form action="{{ route('admin.tickets.destroy', $ticket->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ticket? This action cannot be undone.');">
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
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-400 text-sm">
                                        No tickets found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3 p-3">
                    @forelse($tickets as $ticket)
                        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-2">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[9px] font-mono font-bold">
                                    #{{ $ticket->id }}
                                </a>
                                @php
                                    $pColor = $priorityColors[$ticket->priority] ?? 'gray';
                                    $colorClasses = match($pColor) {
                                        'rose' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                        'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        default => 'bg-gray-50 text-gray-600 border-gray-200'
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-lg border text-[8px] font-black uppercase tracking-widest {{ $colorClasses }}">
                                    {{ $ticket->priority }}
                                </span>
                            </div>

                            <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="block">
                                <h3 class="font-bold text-gray-900 text-sm line-clamp-2 leading-tight">{{ $ticket->subject }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $ticket->category->name }}</span>
                                    <span class="text-[9px] font-bold text-gray-300">•</span>
                                    <span class="text-[9px] font-medium text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </a>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 text-[8px] font-black uppercase">
                                        {{ substr($ticket->user->name, 0, 2) }}
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ $ticket->user->name }}</span>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-{{ $ticket->ticketStatus->color }}-50 text-{{ $ticket->ticketStatus->color }}-600 font-bold text-[8px] uppercase tracking-widest">
                                    {{ $ticket->ticketStatus->name }}
                                </span>
                            </div>

                             @if($ticket->support)
                                <div class="flex items-center gap-2 pt-1 border-gray-50 border-t mt-1">
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Handler:</span>
                                    <span class="text-[9px] font-bold text-indigo-600">{{ $ticket->support->name }}</span>
                                </div>
                             @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 text-sm">
                            No tickets found matching your criteria.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                    {{ $tickets->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
