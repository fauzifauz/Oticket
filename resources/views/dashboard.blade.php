<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('Support Hub') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Global incident registry and personal assistance portal</p>
            </div>
            <div>
                <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-200 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Initialize Request
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Summary Intelligence Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 text-left">
                <div class="bg-white border border-gray-100 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm">
                    <p class="text-[7px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Requests</p>
                    <p class="text-xl sm:text-3xl font-black text-gray-900">{{ $tickets->total() }}</p>
                </div>
                <div class="bg-white border border-gray-100 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm">
                    <p class="text-[7px] sm:text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Active Alerts</p>
                    <p class="text-xl sm:text-3xl font-black text-gray-900">{{ auth()->user()->tickets()->whereRelation('ticketStatus', 'slug', 'open')->count() }}</p>
                </div>
                <div class="bg-white border border-gray-100 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm">
                    <p class="text-[7px] sm:text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">In Processing</p>
                    <p class="text-xl sm:text-3xl font-black text-gray-900">{{ auth()->user()->tickets()->whereRelation('ticketStatus', 'slug', 'in_progress')->count() }}</p>
                </div>
                <div class="bg-emerald-500 p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xl shadow-emerald-100">
                    <p class="text-[7px] sm:text-[9px] font-black text-emerald-100 uppercase tracking-widest mb-1">Resolved Tasks</p>
                    <p class="text-xl sm:text-3xl font-black text-white">{{ auth()->user()->tickets()->whereRelation('ticketStatus', 'slug', 'resolved')->count() }}</p>
                </div>
            </div>

            <!-- Mobile-only: Live Ops Feed & Standard Info (Square Grid) -->
            <div class="lg:hidden grid grid-cols-2 gap-3 text-left">
                <!-- Live Ops Feed (Mobile Square) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative h-40">
                    <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
                        <h4 class="font-black text-[9px] text-gray-900 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-2.5 h-2.5 text-emerald-500 animate-pulse" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                            Live Ops
                        </h4>
                    </div>
                    <div class="p-4 h-full overflow-y-auto">
                        <div class="space-y-3 relative before:absolute before:inset-0 before:ml-1.5 before:-translate-x-px before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                            @forelse($recentActivities as $activity)
                                <a href="{{ route('tickets.show', $activity->ticket->id) }}" class="relative flex items-start group hover:bg-gray-50/80 p-1.5 rounded-lg transition-colors">
                                    <div class="absolute left-0 h-3 w-3 flex items-center justify-center rounded-full bg-indigo-50 border border-indigo-100 mt-0.5 z-10">
                                        <div class="h-1 w-1 rounded-full bg-indigo-600"></div>
                                    </div>
                                    <div class="ml-5 w-full">
                                        <div class="flex justify-between items-start mb-0.5">
                                            <span class="text-[8px] font-black text-indigo-600 uppercase tracking-widest truncate max-w-[60px]">{{ $activity->user->name ?? 'Sup' }}</span>
                                            <span class="text-[7px] font-bold text-gray-400">{{ $activity->created_at->shortAbsoluteDiffForHumans() }}</span>
                                        </div>
                                        <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest mb-0.5">
                                            Ticket #{{ Str::limit($activity->ticket->uuid, 6, '') }}
                                        </p>
                                        <p class="text-[8px] text-gray-600 font-bold leading-tight line-clamp-2">
                                            "{{ $activity->message }}"
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-2">
                                    <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest">No signals</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Standard Operational Info (Mobile Square) -->
                <div class="bg-indigo-600 rounded-2xl p-4 shadow-xl shadow-indigo-100 overflow-hidden relative group text-left h-40 flex flex-col justify-center">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                    <h4 class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-2">Standard Ops</h4>
                    <p class="text-[9px] font-bold text-white leading-relaxed uppercase tracking-tight line-clamp-4">
                        Quality standards maintained. Rate resolved tickets to improve PINS infrastructure.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 animate-fade-in-down text-left">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                
                <!-- Left Column (Ticket List) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden h-full">
                        <div class="px-5 sm:px-8 py-4 sm:py-6 border-b border-gray-100 bg-white flex justify-between items-center">
                            <div class="text-left">
                                <h3 class="text-[10px] sm:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-1 h-1 sm:w-2 sm:h-2 rounded-full bg-indigo-500"></span>
                                    My Incident Ledger
                                </h3>
                            </div>
                        </div>

                        <div class="overflow-x-auto text-left">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="px-6 py-4 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest">Protocol ID</th>
                                        <th class="px-4 py-4 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest">Subject Matrix</th>
                                        <th class="px-4 py-4 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest">Priority</th>
                                        <th class="px-4 py-4 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                        <th class="px-6 py-4 text-[9px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 uppercase tracking-tight">
                                    @forelse($tickets as $ticket)
                                    <tr class="hover:bg-gray-50/70 transition-all duration-200 group text-[10px] font-bold text-gray-700 text-left">
                                        <td class="px-6 py-4 font-mono text-indigo-600/60">
                                            #{{ Str::limit($ticket->uuid, 6, '') }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <p class="text-gray-900 font-extrabold group-hover:text-indigo-600 transition-colors">{{ Str::limit($ticket->subject, 25) }}</p>
                                            <p class="text-[9px] text-gray-400">{{ $ticket->category->name }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-left">
                                            @php
                                                $prioColor = match(strtolower($ticket->priority)) {
                                                    'high', 'critical', 'urgent' => 'text-rose-500 bg-rose-50',
                                                    'medium' => 'text-amber-500 bg-amber-50',
                                                    default => 'text-indigo-500 bg-indigo-50'
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-md text-[8px] font-black {{ $prioColor }} tracking-widest">
                                                {{ $ticket->priority }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            @php
                                                $statusColor = match(strtolower($ticket->status)) {
                                                    'open' => 'bg-rose-500',
                                                    'in_progress', 'assigned' => 'bg-indigo-500',
                                                    'resolved' => 'bg-emerald-500',
                                                    default => 'bg-slate-400'
                                                };
                                            @endphp
                                            <div class="flex items-center justify-center gap-1.5">
                                                <div class="w-1.5 h-1.5 rounded-full {{ $statusColor }}"></div>
                                                <span class="text-[8px] font-black text-gray-900 uppercase tracking-widest">{{ str_replace('_', ' ', $ticket->status) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('tickets.show', $ticket->id) }}" class="inline-flex items-center px-4 py-1.5 bg-white border border-gray-200 rounded-lg text-[8px] font-black uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 shadow-sm transition-all">
                                                Examine
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-200">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                                </div>
                                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No active protocols</p>
                                                <a href="{{ route('tickets.create') }}" class="text-[9px] font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-widest underline underline-offset-4">Initialize Request</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if($tickets->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                            {{ $tickets->links() }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column (Sidebar Widgets) -->
                <div class="space-y-6">
                    
                    <!-- Widget 1: Live Ops Feed -->
                    <div class="hidden lg:block bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
                            <h4 class="font-black text-[10px] text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-3 h-3 text-emerald-500 animate-pulse" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                Live Ops Feed
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                                @forelse($recentActivities as $activity)
                                    <div class="relative flex items-start group">
                                        <div class="absolute left-0 h-4 w-4 flex items-center justify-center rounded-full bg-indigo-50 border border-indigo-100 mt-0.5 z-10">
                                            <div class="h-1.5 w-1.5 rounded-full bg-indigo-600"></div>
                                        </div>
                                        <div class="ml-8 w-full">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">{{ $activity->user->name ?? 'Support' }}</span>
                                                <span class="text-[8px] font-bold text-gray-400">{{ $activity->created_at->diffForHumans(null, true) }}</span>
                                            </div>
                                            <p class="text-[10px] text-gray-600 font-bold leading-relaxed mb-1.5">
                                                Replied to <a href="{{ route('tickets.show', $activity->ticket->id) }}" class="text-gray-900 hover:underline">#{{ Str::limit($activity->ticket->uuid, 6, '') }}</a>
                                            </p>
                                            <div class="text-[10px] text-gray-500 italic bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                "{{ Str::limit($activity->message, 50) }}"
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No recent transmissions</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Widget 2: System Broadcast -->
                    <div class="bg-gray-900 rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-200 p-6 text-white relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-indigo-500/30 transition-all duration-700"></div>
                        
                        <div class="relative z-10">
                            <h4 class="flex items-center gap-2 font-black text-[10px] text-indigo-300 uppercase tracking-widest mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                System Broadcast
                            </h4>
                            <div class="space-y-3">
                                <div class="p-3 bg-white/5 rounded-xl border border-white/5 backdrop-blur-sm">
                                    <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">{{ $broadcast['title'] }}</p>
                                    <p class="text-[10px] text-gray-300 font-bold leading-relaxed">
                                        {{ $broadcast['message'] }}
                                    </p>
                                </div>
                                <div class="p-3 bg-white/5 rounded-xl border border-white/5 backdrop-blur-sm">
                                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">Tip of the Day</p>
                                    <p class="text-[10px] text-gray-300 font-bold leading-relaxed">
                                        Use "Initialize Request" for urgent network anomalies.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Global Help Card (Desktop Only) -->
            <div class="hidden lg:block mt-8 bg-indigo-600 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl shadow-indigo-100 overflow-hidden relative group text-left">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="max-w-2xl">
                        <h4 class="text-[9px] sm:text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-4">Standard Operational Info</h4>
                        <p class="text-[11px] sm:text-xs font-bold text-white leading-relaxed uppercase tracking-tight">
                            Reporting an incident ensures our operational quality standards are maintained. Once a ticket is resolved, please provide a service rating to help us improve the OTicket infrastructure for all PINS employees.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
