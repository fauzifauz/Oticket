<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-left">
            <div class="flex items-center gap-4">
                <a href="{{ route('support.tickets.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight flex items-center gap-3">
                        {{ __('Manage Ticket') }}
                        <span class="text-indigo-600 font-mono text-lg">#{{ strtoupper(substr($ticket->uuid, 0, 8)) }}</span>
                    </h2>
                    <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Actionable oversight of support request</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-4 py-1.5 bg-gray-100 text-gray-700 border border-gray-200 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                    {{ str_replace('_', ' ', $ticket->status) }}
                </span>
                <span class="px-4 py-1.5 {{ $ticket->priority == 'critical' ? 'bg-rose-500 text-white' : ($ticket->priority == 'high' ? 'bg-amber-500 text-white' : 'bg-indigo-500 text-white') }} rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                    {{ $ticket->priority }} PRIORITY
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Main Content (8 Columns) -->
                <div class="lg:col-span-8 space-y-8 text-left">
                    
                    <!-- Ticket Description Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-indigo-500 flex items-center justify-center text-white text-base font-black uppercase">
                                        {{ substr($ticket->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Reported by</p>
                                        <h3 class="text-lg font-black text-gray-900 leading-tight uppercase">{{ $ticket->user->name }}</h3>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Submitted</p>
                                    <p class="text-xs font-black text-gray-700 leading-tight uppercase tracking-tighter">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50/50 rounded-2xl p-8 border border-gray-50 mb-8">
                                <h1 class="text-2xl font-black text-gray-900 mb-4 tracking-tight leading-tight uppercase">{{ $ticket->subject }}</h1>
                                <div class="prose prose-sm max-w-none text-gray-700 font-semibold leading-relaxed uppercase tracking-tight">
                                    {!! nl2br(e($ticket->description)) !!}
                                </div>
                            </div>
                            
                            <!-- Mobile-only: User Info & Metadata (Compact) -->
                            <div class="lg:hidden grid grid-cols-2 gap-3 mb-8">
                                <!-- User Info Compact Card -->
                                <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden text-white p-3">
                                    <p class="text-[8px] font-bold text-gray-500 uppercase tracking-wider mb-2">User Info</p>
                                    <div class="space-y-2">
                                        <div>
                                            <p class="text-[7px] text-gray-500 uppercase tracking-tighter">Name</p>
                                            <p class="text-[10px] font-black uppercase truncate">{{ $ticket->user->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[7px] text-gray-500 uppercase tracking-tighter">Email</p>
                                            <p class="text-[10px] font-black lowercase truncate">{{ $ticket->user->email }}</p>
                                        </div>
                                        <div class="flex justify-between">
                                            <div>
                                                <p class="text-[7px] text-gray-500 uppercase tracking-tighter">Phone</p>
                                                <p class="text-[10px] font-black uppercase">{{ $ticket->user->phone ?? 'N/A' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[7px] text-gray-500 uppercase tracking-tighter">Dept</p>
                                                <p class="text-[10px] font-black uppercase">{{ $ticket->user->department ?? 'Standard' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Metadata Compact Card -->
                                <div class="bg-indigo-600 rounded-xl shadow-lg overflow-hidden text-white p-3">
                                    <p class="text-[8px] font-bold text-indigo-300 uppercase tracking-wider mb-3">Meta Info</p>
                                    
                                    <div class="space-y-3">
                                        <!-- SLA STATUS Row -->
                                        <div>
                                            <div class="flex items-center justify-between mb-0.5">
                                                <p class="text-[7px] text-indigo-300 uppercase">SLA Status</p>
                                                @if($ticket->sla_due_at && $ticket->sla_due_at > now() && !in_array($ticket->status, ['resolved', 'closed']))
                                                    <span class="text-[6px] font-black bg-emerald-500 text-white px-1.5 py-0.5 rounded uppercase">Active</span>
                                                @endif
                                            </div>
                                            <p class="text-[10px] font-black tracking-tight leading-none">
                                                {{ $ticket->sla_due_at ? $ticket->sla_due_at->format('d M Y H:i') : 'OFF-PROTOCOL' }}
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <!-- ASSET (Category) -->
                                            <div>
                                                <p class="text-[7px] text-indigo-300 uppercase mb-0.5">Asset</p>
                                                <p class="text-[9px] font-bold truncate tracking-tight">{{ strtoupper($ticket->category->name) }}</p>
                                            </div>

                                            <!-- QUEUE ID -->
                                            <div class="text-right">
                                                <p class="text-[7px] text-indigo-300 uppercase mb-0.5">Queue ID</p>
                                                <p class="text-[9px] font-bold tracking-widest">#{{ $ticket->id }}</p>
                                            </div>
                                        </div>

                                        <!-- SYNC ACTIVE (Updated) -->
                                        <div class="flex items-center gap-1.5 pt-1 border-t border-indigo-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <p class="text-[7px] font-bold text-indigo-200 uppercase tracking-widest">Sync Active • {{ $ticket->updated_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($ticket->attachments->count() > 0)
                                <div class="space-y-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Evidence & Attachments</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($ticket->attachments as $attachment)
                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="flex items-center gap-3 p-4 bg-white border border-gray-100 rounded-2xl hover:border-indigo-100 hover:shadow-sm transition-all group">
                                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-[10px] font-black text-gray-900 uppercase truncate">{{ basename($attachment->file_path) }}</p>
                                                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Click to inspect</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Feedback Section -->
                    @if($ticket->feedback)
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2rem] shadow-xl shadow-indigo-100 overflow-hidden relative group">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500"></div>
                            <div class="p-8 relative z-10 text-left">
                                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                                    <div class="text-center md:text-left shrink-0">
                                        <h3 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.3em] mb-4">Customer Satisfaction Feedback</h3>
                                        <div class="flex items-center justify-center md:justify-start gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-8 h-8 {{ $i <= $ticket->feedback->rating ? 'text-amber-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.5)]' : 'text-indigo-400/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($ticket->feedback->comment)
                                        <div class="flex-1 bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 shadow-inner">
                                            <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-3 opacity-60">Employee Narrative:</p>
                                            <p class="text-xs font-bold text-white italic leading-relaxed uppercase tracking-tight">"{{ $ticket->feedback->comment }}"</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Conversation History -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between px-1 text-left">
                            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                Protocol Conversation
                            </h3>
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $ticket->responses->count() }} Interactions Recorded</span>
                        </div>

                        <div class="space-y-4">
                            @forelse($ticket->responses as $response)
                                <div class="flex flex-col {{ $response->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                    <div class="{{ $response->user_id === auth()->id() ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'bg-white text-gray-800 border border-gray-100 shadow-sm' }} p-6 rounded-3xl rounded-tl-none @if($response->user_id === auth()->id()) rounded-tl-3xl rounded-tr-none @endif transition-transform hover:scale-[1.01] max-w-[90%]">
                                        <div class="flex items-center justify-between mb-3 gap-8 text-left">
                                            <span class="text-[10px] font-black uppercase tracking-widest @if($response->user_id === auth()->id()) text-indigo-100 @else text-gray-400 @endif">
                                                {{ $response->user->name }} ({{ strtoupper($response->user->role) }})
                                            </span>
                                            <span class="text-[9px] font-bold uppercase tracking-tighter opacity-60">{{ $response->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-[13px] font-bold leading-relaxed uppercase tracking-tight text-left">
                                            {!! nl2br(e($response->message)) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 bg-white rounded-3xl border border-dashed border-gray-200 text-center">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No protocol response recorded yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Reply & Status Box -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                        <div class="p-8">
                            <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-6">Support Action Layer</h4>
                            <form action="{{ route('support.tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Update Operational Status</label>
                                        <select name="status" class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status->slug }}" {{ $ticket->ticketStatus?->slug == $status->slug ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Inherent Priority Override</label>
                                        <select name="priority" class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight">
                                            @foreach($slaRules as $sla)
                                                <option value="{{ $sla->priority }}" {{ $ticket->priority == $sla->priority ? 'selected' : '' }}>{{ $sla->priority }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Official Response Payload</label>
                                    <textarea name="message" rows="5" class="w-full bg-gray-50 border-gray-100 rounded-2xl text-xs font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight leading-relaxed" placeholder="INSERT YOUR INTERVENTION RECORD HERE..."></textarea>
                                </div>

                                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-300">
                                    Submit Update & Dispatch Response
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (4 Columns) - Desktop Only -->
                <div class="hidden lg:block lg:col-span-4 space-y-8 text-left">
                    
                    <!-- User Info Card -->
                    <div class="bg-gray-900 rounded-3xl p-8 shadow-xl shadow-gray-200 overflow-hidden relative group">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
                        
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Full Name</p>
                                    <p class="text-xs font-black text-white uppercase">{{ $ticket->user->name }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Secure Email</p>
                                    <p class="text-xs font-black text-white lowercase truncate">{{ $ticket->user->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Contact Phone</p>
                                    <p class="text-xs font-black text-white uppercase">{{ $ticket->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Division / Dept</p>
                                    <p class="text-xs font-black text-white uppercase">{{ $ticket->user->department ?? 'Standard' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Meta Card -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 text-left">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Technical Meta Info</h4>
                        <div class="space-y-6">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">SLA Protocol Deadline</span>
                                    <span class="text-[9px] font-black {{ $ticket->sla_due_at && $ticket->sla_due_at->isPast() ? 'text-rose-600' : 'text-emerald-600' }} uppercase px-2 py-0.5 bg-gray-50 rounded">
                                        {{ $ticket->sla_due_at && $ticket->sla_due_at->isPast() ? 'EXPIRED' : 'ACTIVE' }}
                                    </span>
                                </div>
                                <p class="text-xs font-black text-gray-900 uppercase">{{ $ticket->sla_due_at ? $ticket->sla_due_at->format('d M Y H:i') : 'OFF-PROTOCOL' }}</p>
                                @if($ticket->sla_due_at && $ticket->sla_due_at->isPast())
                                    <div class="mt-2 p-3 bg-rose-50 border border-rose-100 rounded-xl">
                                        <p class="text-[9px] font-black text-rose-600 uppercase tracking-tighter">Warning: Support interaction exceeds SLA protocol parameters.</p>
                                    </div>
                                @endif
                            </div>

                            <hr class="border-gray-50">

                            <div class="flex justify-between items-center text-left">
                                <div>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Infrastructure Asset</p>
                                    <p class="text-xs font-black text-gray-900 uppercase">{{ $ticket->category->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Queue ID</p>
                                    <p class="text-xs font-black text-gray-900 uppercase">#{{ $ticket->id }}</p>
                                </div>
                            </div>

                            <div class="pt-4 mt-4 border-t border-gray-50 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Sync Active &bull; {{ now()->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
