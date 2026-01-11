<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 sm:gap-4">
            <a href="{{ route('admin.tickets.index') }}" class="p-1.5 sm:p-2 bg-white border border-gray-200 rounded-lg sm:rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition duration-150">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-lg sm:text-2xl text-gray-900 tracking-tight leading-tight">
                    {{ __('Ticket Details') }} <span class="text-gray-400 font-mono text-base sm:text-xl ml-1">#{{ strtoupper(substr($ticket->uuid, 0, 8)) }}</span>
                </h2>
                <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Reference ID: {{ $ticket->uuid }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-left">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content (Ticket & Responses) -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Ticket Description Card -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 sm:p-8">
                            <div class="flex items-center justify-between mb-5 sm:mb-6">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-indigo-500 flex items-center justify-center text-white text-sm sm:text-base font-black uppercase">
                                        {{ substr($ticket->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-[9px] sm:text-xs font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Reported by</p>
                                        <h3 class="text-base sm:text-lg font-black text-gray-900 leading-tight">{{ $ticket->user->name }}</h3>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Submitted</p>
                                    <p class="text-[10px] sm:text-xs font-black text-gray-700 leading-tight uppercase tracking-tighter">{{ $ticket->created_at->format('d M y H:i') }}</p>
                                </div>
                            </div>

                            <div class="bg-gray-50/50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-gray-50 mb-5 sm:mb-6">
                                <h1 class="text-lg sm:text-2xl font-black text-gray-900 mb-3 sm:mb-4 tracking-tight leading-tight uppercase">{{ $ticket->subject }}</h1>
                                <div class="prose prose-sm max-w-none text-gray-700 font-bold leading-relaxed uppercase tracking-tight text-[11px] sm:text-sm">
                                    {!! nl2br(e($ticket->description)) !!}
                                </div>
                            </div>
                            
                            @if($ticket->attachments->count() > 0)
                                <div class="flex flex-wrap gap-3 mt-4 pt-6 border-t border-gray-50">
                                    @foreach($ticket->attachments as $attachment)
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 rounded-2xl group hover:border-indigo-600 hover:shadow-md transition-all duration-200">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Attachment</p>
                                                <p class="text-xs font-black text-gray-700 group-hover:text-indigo-600 transition-colors uppercase tracking-tighter truncate max-w-[150px]">{{ $attachment->file_name }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Feedback Section -->
                    @if($ticket->feedback)
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl sm:rounded-3xl shadow-xl shadow-indigo-100 overflow-hidden relative group">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500"></div>
                            <div class="p-6 sm:p-8 relative z-10">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                                    <div class="text-center sm:text-left">
                                        <h3 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.3em] mb-3">Customer Satisfaction Feedback</h3>
                                        <div class="flex items-center justify-center sm:justify-start gap-1 mb-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-6 h-6 sm:w-8 sm:h-8 {{ $i <= $ticket->feedback->rating ? 'text-amber-400 drop-shadow-[0_0_8px_rgba(251,191,36,0.4)]' : 'text-indigo-400/50' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($ticket->feedback->comment)
                                        <div class="flex-1 bg-white/10 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-inner">
                                            <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-2 opacity-60">Employee Comments:</p>
                                            <p class="text-xs sm:text-sm font-bold text-white italic leading-relaxed uppercase tracking-tight">"{{ $ticket->feedback->comment }}"</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Mobile-only: User Info & Metadata (Compact) -->
                    <div class="lg:hidden grid grid-cols-2 gap-3">
                        <!-- User Info Compact Card -->
                        <div class="bg-gray-900 rounded-xl shadow-lg overflow-hidden text-white p-3">
                            <p class="text-[8px] font-bold text-gray-500 uppercase tracking-wider mb-2">User Info</p>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-[7px] text-gray-500 uppercase">Name</p>
                                    <p class="text-[9px] font-bold truncate">{{ $ticket->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-[7px] text-gray-500 uppercase">Email</p>
                                    <p class="text-[8px] font-bold lowercase truncate">{{ $ticket->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-[7px] text-gray-500 uppercase">Phone</p>
                                    <p class="text-[9px] font-bold">{{ $ticket->user->phone ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[7px] text-gray-500 uppercase">Dept</p>
                                    <p class="text-[9px] font-bold truncate">{{ $ticket->user->department ?? 'Standard' }}</p>
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

                    <!-- Conversation / Response Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 px-4">
                            <div class="h-px flex-1 bg-gray-200"></div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Support Conversation Log</span>
                            <div class="h-px flex-1 bg-gray-200"></div>
                        </div>

                        <div class="space-y-4">
                            @forelse($ticket->responses as $response)
                                @php
                                    $isSelf = $response->user_id === auth()->id();
                                    $role = $response->user->role;
                                    $colorClass = match($role) {
                                        'admin' => 'bg-indigo-600 text-white shadow-indigo-100',
                                        'support' => 'bg-emerald-600 text-white shadow-emerald-100',
                                        default => 'bg-gray-500 text-white shadow-gray-100',
                                    };
                                @endphp
                                <div class="flex flex-col {{ $isSelf ? 'items-end' : 'items-start' }}">
                                    <div class="max-w-[95%] sm:max-w-[85%] {{ $colorClass }} p-4 sm:p-6 rounded-xl sm:rounded-3xl rounded-tl-none {{ $isSelf ? 'rounded-tl-xl sm:rounded-tl-3xl rounded-tr-none' : '' }} transition-transform hover:scale-[1.01] shadow-lg">
                                        <div class="flex items-center justify-between mb-2 sm:mb-3 gap-6 sm:gap-8">
                                            <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-widest opacity-90">{{ $response->user->name }}</span>
                                            <span class="text-[8px] sm:text-[9px] font-bold uppercase tracking-tighter opacity-70">{{ $response->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-[12px] sm:text-[13px] font-bold leading-relaxed uppercase tracking-tight">
                                            {!! nl2br(e($response->message)) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white border border-dashed border-gray-200 rounded-3xl p-12 text-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No communication history established</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Add Response Card -->
                        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden transform transition duration-300 focus-within:ring-4 focus-within:ring-indigo-500/5 focus-within:border-indigo-100">
                            <div class="px-5 sm:px-8 py-4 sm:py-6 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center">
                                <h3 class="font-extrabold text-gray-900 uppercase text-[10px] sm:text-xs tracking-widest">Transmit Official Response</h3>
                                <div class="flex items-center gap-2">
                                    <span class="w-1 h-1 sm:w-2 sm:h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[8px] sm:text-[9px] font-black text-emerald-600 uppercase tracking-widest">Secure Uplink</span>
                                </div>
                            </div>
                            <div class="p-4 sm:p-8">
                                <form action="{{ route('admin.tickets.store-response', $ticket->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4 sm:mb-6 group">
                                        <textarea name="message" rows="4" class="w-full bg-gray-50/50 border-gray-100 rounded-xl sm:rounded-2xl text-[11px] sm:text-xs font-black text-gray-800 uppercase tracking-tight placeholder:text-gray-300 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all duration-200" placeholder="TYPE YOUR RESPONSE PROTOCOL HERE..." required></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-indigo-600 text-white rounded-xl sm:rounded-2xl text-[11px] sm:text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-150 transform active:scale-95 justify-center">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            Post Response
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (Controls) -->
                <div class="space-y-8">
                    <!-- Ticket Controller Card -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-gray-100 bg-gray-50/30">
                            <h4 class="font-black text-[10px] sm:text-xs text-gray-900 uppercase tracking-widest">Management Override</h4>
                        </div>
                        <div class="p-4 sm:p-8 space-y-5 sm:space-y-6">
                            @if(session('success'))
                                <div class="p-3 sm:p-4 bg-emerald-50 border border-emerald-100 rounded-xl sm:rounded-2xl flex items-center gap-2 sm:gap-3 text-emerald-700 text-[9px] sm:text-[10px] font-black uppercase tracking-widest">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Updated Successfully
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="p-3 sm:p-4 bg-rose-50 border border-rose-100 rounded-xl sm:rounded-2xl text-rose-700 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.tickets.update', $ticket->id) }}" class="space-y-5 sm:space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- Status -->
                                <div class="space-y-1.5 sm:space-y-2">
                                    <label class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Global Status</label>
                                    <select name="status" class="w-full bg-gray-50 border-gray-100 rounded-xl sm:rounded-2xl text-[10px] sm:text-xs font-black text-gray-800 uppercase tracking-tighter focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->slug }}" {{ $ticket->status == $status->slug ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Priority -->
                                <div class="space-y-1.5 sm:space-y-2">
                                    <label class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Inherent Priority</label>
                                    <select name="priority" class="w-full bg-gray-50 border-gray-100 rounded-xl sm:rounded-2xl text-[10px] sm:text-xs font-black text-gray-800 uppercase tracking-tighter focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all">
                                        @foreach($slaRules as $sla)
                                            <option value="{{ $sla->priority }}" {{ $ticket->priority == $sla->priority ? 'selected' : '' }}>{{ $sla->priority }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Assign Support -->
                                <div class="space-y-1.5 sm:space-y-2">
                                    <label class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Assigned Operative</label>
                                    <select name="support_id" class="w-full bg-gray-50 border-gray-100 rounded-xl sm:rounded-2xl text-[10px] sm:text-xs font-black text-gray-800 uppercase tracking-tighter focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all">
                                        <option value="">NO ASSIGNEE</option>
                                        @foreach($supportStaff as $staff)
                                            <option value="{{ $staff->id }}" {{ $ticket->support_id == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 sm:py-4 rounded-xl sm:rounded-2xl text-[9px] sm:text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-indigo-100 transition-all transform active:scale-95">
                                    Commit Changes
                                </button>
                            </form>
                            
                            <div class="h-px bg-gray-100"></div>

                            <form method="POST" action="{{ route('admin.tickets.destroy', $ticket->id) }}" onsubmit="return confirm('Purge this high-priority data permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full p-3 sm:p-4 text-rose-500 bg-rose-50/50 hover:bg-rose-500 hover:text-white border border-rose-100 rounded-xl sm:rounded-2xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition-all">
                                    Terminal Deletion
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- User Info Card (Simplified) -->
                    <div class="hidden lg:block bg-gray-900 rounded-2xl sm:rounded-3xl shadow-xl shadow-gray-200 overflow-hidden text-white">
                        <div class="p-5 sm:p-8 space-y-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Name</p>
                                    <p class="text-xs font-black uppercase">{{ $ticket->user->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="overflow-hidden flex-1">
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Email</p>
                                    <p class="text-xs font-black lowercase truncate">{{ $ticket->user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Phone</p>
                                    <p class="text-xs font-black uppercase">{{ $ticket->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">Department</p>
                                    <p class="text-xs font-black uppercase">{{ $ticket->user->department ?? 'Standard' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meta Intel Card -->
                    <div class="hidden lg:block bg-indigo-600 rounded-2xl sm:rounded-3xl shadow-xl shadow-indigo-100 overflow-hidden text-white">
                        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-indigo-500 bg-indigo-700/30">
                            <h4 class="font-black text-[10px] sm:text-xs uppercase tracking-widest">Metadata Intel</h4>
                        </div>
                        <div class="p-5 sm:p-8 space-y-6">
                            <div class="flex items-center gap-4 group">
                                @php
                                    $catPrio = $ticket->category->priority ?? 'normal';
                                    $prioColor = $priorityColors[$catPrio] ?? 'indigo';
                                    $bgColorClasses = [
                                        'gray' => 'bg-gray-500',
                                        'rose' => 'bg-rose-500',
                                        'indigo' => 'bg-indigo-500',
                                        'emerald' => 'bg-emerald-500',
                                        'amber' => 'bg-amber-500',
                                        'blue' => 'bg-blue-500',
                                        'violet' => 'bg-violet-500',
                                    ];
                                    $bgColor = $bgColorClasses[$prioColor] ?? 'bg-indigo-500';
                                @endphp
                                <div class="w-10 h-10 rounded-xl {{ $bgColor }} flex items-center justify-center group-hover:bg-white group-hover:text-indigo-600 transition-all shadow-sm">
                                    <svg class="w-5 h-5 text-white group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-indigo-300 uppercase tracking-widest leading-none mb-1">Classification ({{ ucfirst($catPrio) }})</p>
                                    <p class="text-[11px] font-black uppercase tracking-tighter">{{ $ticket->category->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 group">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center group-hover:bg-white group-hover:text-indigo-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-indigo-300 uppercase tracking-widest leading-none mb-1">SLA Deadline</p>
                                    <p class="text-[11px] font-black uppercase tracking-tighter">{{ $ticket->sla_due_at ? $ticket->sla_due_at->format('d M Y H:i') : 'OFF-PROTOCOL' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 group">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center group-hover:bg-white group-hover:text-indigo-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold text-indigo-300 uppercase tracking-widest leading-none mb-1">Last Transmission</p>
                                    <p class="text-[11px] font-black uppercase tracking-tighter">{{ $ticket->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
