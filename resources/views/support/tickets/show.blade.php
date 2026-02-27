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

    <div class="py-12 bg-gray-50/50" x-data="{ 
        openPreview(url, fileName) {
            this.previewUrl = url;
            this.previewName = fileName;
            const extension = fileName.split('.').pop().toLowerCase();
            if (['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(extension)) {
                this.previewType = 'image';
            } else if (extension === 'pdf') {
                this.previewType = 'pdf';
            } else {
                window.open(url, '_blank');
                return;
            }
            this.showPreview = true;
        }
    }">
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
                                            @php
                                                $extension = pathinfo($attachment->file_name, PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
                                                $label = $isImage ? 'View Image' : (strtolower($extension) === 'pdf' ? 'View Document' : 'Attachment');
                                            @endphp
                                            <a href="{{ Storage::url($attachment->file_path) }}" 
                                               @click.prevent="openPreview('{{ Storage::url($attachment->file_path) }}', '{{ $attachment->file_name }}')"
                                               class="flex items-center gap-2 lg:gap-3 p-3 lg:p-4 bg-gray-50 rounded-xl lg:rounded-2xl border border-gray-100 hover:border-indigo-200 hover:bg-white transition-all group cursor-zoom-in">
                                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-white rounded-lg lg:rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-indigo-500 transition-colors">
                                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                                <div>
                                                    <p class="text-[8px] lg:text-[9px] font-black text-gray-400 uppercase tracking-widest group-hover:text-indigo-400">{{ $label }}</p>
                                                    <p class="text-[9px] lg:text-[10px] font-black text-gray-900 uppercase tracking-tight">{{ Str::limit($attachment->file_name, 20) }}</p>
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
                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl md:rounded-[2rem] shadow-xl shadow-indigo-100 overflow-hidden relative group">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-500"></div>
                            <div class="p-5 md:p-8 relative z-10 text-left">
                                <div class="flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8">
                                    <div class="text-center md:text-left shrink-0">
                                        <h3 class="text-[9px] md:text-[10px] font-black text-indigo-200 uppercase tracking-widest md:tracking-[0.3em] mb-2 md:mb-4">Customer Satisfaction Feedback</h3>
                                        <div class="flex items-center justify-center md:justify-start gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 md:w-8 md:h-8 {{ $i <= $ticket->feedback->rating ? 'text-amber-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.5)]' : 'text-indigo-400/40' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    @if($ticket->feedback->comment)
                                        <div class="flex-1 bg-white/10 backdrop-blur-md rounded-2xl p-4 md:p-6 border border-white/10 shadow-inner w-full">
                                            <p class="text-[9px] md:text-[10px] font-black text-indigo-100 uppercase tracking-widest mb-1 md:mb-3 opacity-60">Employee Narrative:</p>
                                            <p class="text-[10px] md:text-xs font-bold text-white italic leading-relaxed uppercase tracking-tight">"{{ $ticket->feedback->comment }}"</p>
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
                                    <div class="max-w-[90%] {{ $colorClass }} p-6 rounded-3xl rounded-tl-none {{ $isSelf ? 'rounded-tl-3xl rounded-tr-none' : '' }} transition-transform hover:scale-[1.01] shadow-lg">
                                        <div class="flex items-center justify-between mb-3 gap-8 text-left">
                                            <span class="text-[10px] font-black uppercase tracking-widest opacity-90">
                                                {{ $response->user->name }} ({{ strtoupper($response->user->role) }})
                                            </span>
                                            <span class="text-[9px] font-bold uppercase tracking-tighter opacity-70">{{ $response->created_at->diffForHumans() }}</span>
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

                <!-- Sidebar / Metadata -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- AI Similar Tickets Card -->
                    <div class="bg-gradient-to-br from-gray-900 to-indigo-950 rounded-xl shadow-lg overflow-hidden text-white relative group border border-white/5">
                        <div class="px-4 py-3 border-b border-white/10 bg-black/40 flex justify-between items-center relative z-10">
                            <h4 class="font-black text-[10px] uppercase tracking-widest flex items-center gap-2 text-indigo-300">
                                <svg class="w-3.5 h-3.5 text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                AI Similar Tickets
                            </h4>
                            <span class="bg-emerald-500/20 text-emerald-300 text-[9px] font-bold px-2 py-0.5 rounded border border-emerald-500/20">
                                {{ !empty($similarTickets) ? count($similarTickets) : 0 }} Found
                            </span>
                        </div>
                        
                        <div class="p-3 space-y-4 relative z-10">
                            @if(!empty($similarTickets) && count($similarTickets) > 0)
                                @foreach($similarTickets as $match)
                                    <div class="group/item relative pl-3 border-l border-indigo-500/30 hover:border-emerald-500 transition-colors duration-300 text-left">
                                        <div class="flex justify-between items-start mb-1 gap-2">
                                            <a href="{{ route('support.tickets.show', $match['id']) }}" target="_blank" class="text-[11px] font-black text-white hover:text-emerald-300 transition-colors uppercase tracking-tight line-clamp-1 leading-tight">
                                                {{ $match['subject'] }}
                                            </a>
                                            <span class="text-[9px] font-bold text-emerald-400 whitespace-nowrap">{{ $match['similarity'] }}%</span>
                                        </div>
                                        
                                        <div class="bg-white/5 rounded-lg p-2.5 mb-2 border border-white/5 group-hover/item:bg-white/10 transition-colors text-left">
                                            <p class="text-[9px] text-gray-300 line-clamp-2 leading-relaxed font-medium">
                                                "{{ $match['solution'] ?: 'No explicit solution recorded.' }}"
                                            </p>
                                        </div>

                                        <a href="{{ route('support.tickets.show', $match['id']) }}" target="_blank" class="inline-flex items-center text-[9px] font-bold text-indigo-300 hover:text-white uppercase tracking-wider transition-colors group-hover/item:translate-x-1 duration-300">
                                            Access Protocol
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-4 text-center">
                                    <p class="text-[10px] font-bold text-indigo-300/60 uppercase tracking-widest">No similar protocols mapped</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="bg-black/40 p-2 text-center border-t border-white/5">
                            <p class="text-[8px] text-indigo-400/40 uppercase tracking-widest font-bold font-mono">Powered by O-ENGINE</p>
                        </div>
                    </div>

                    <!-- Additional Sidebar Modules (Desktop Only) -->
                    <div class="hidden lg:block space-y-8">


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

    <!-- Attachment Preview Modal -->
    <div x-show="showPreview" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
        <div x-show="showPreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm transition-opacity" @click="showPreview = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="showPreview" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative w-full max-w-[95vw] bg-white rounded-3xl shadow-2xl overflow-hidden" @click.away="showPreview = false">
                <button @click="showPreview = false" class="absolute top-4 right-4 z-10 p-2 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="p-2 sm:p-4 h-[90vh] flex flex-col">
                    <template x-if="previewType === 'image'">
                        <div class="flex-1 flex items-center justify-center overflow-auto">
                            <img :src="previewUrl" class="w-full h-full object-contain rounded-xl shadow-lg border border-gray-100">
                        </div>
                    </template>
                    <template x-if="previewType === 'pdf'">
                        <div class="flex-1 flex flex-col h-full">
                            <iframe :src="previewUrl" class="flex-1 w-full rounded-xl border border-gray-100" frameborder="0"></iframe>
                        </div>
                    </template>

                    <div class="mt-4 flex justify-between items-center px-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate max-w-[60%]" x-text="previewName"></p>
                        <a :href="previewUrl" :download="previewName" target="_blank" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Open / Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
