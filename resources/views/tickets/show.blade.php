<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 text-left">
            <div class="flex flex-col md:flex-row md:items-center gap-4 w-full md:w-auto">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <div>
                        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                            {{ __('Incident Dossier') }}
                        </h2>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">UUID: {{ $ticket->uuid }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 md:hidden ml-14">
                    @php
                        $statusColor = match(strtolower($ticket->status)) {
                            'open' => 'bg-rose-500 text-white',
                            'in_progress', 'assigned' => 'bg-indigo-500 text-white',
                            'resolved' => 'bg-emerald-500 text-white',
                            default => 'bg-slate-400 text-white'
                        };
                        $prioColor = match(strtolower($ticket->priority)) {
                            'high', 'critical', 'urgent' => 'text-rose-600 border-rose-100 bg-rose-50',
                            'medium' => 'text-amber-600 border-amber-100 bg-amber-50',
                            default => 'text-indigo-600 border-indigo-100 bg-indigo-50'
                        };
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusColor }}">
                        {{ str_replace('_', ' ', $ticket->status) }}
                    </span>
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $prioColor }}">
                        {{ $ticket->priority }} PRIORITY
                    </span>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-3">
                @php
                    $statusColor = match(strtolower($ticket->status)) {
                        'open' => 'bg-rose-500 text-white',
                        'in_progress', 'assigned' => 'bg-indigo-500 text-white',
                        'resolved' => 'bg-emerald-500 text-white',
                        default => 'bg-slate-400 text-white'
                    };
                    $prioColor = match(strtolower($ticket->priority)) {
                        'high', 'critical', 'urgent' => 'text-rose-600 border-rose-100 bg-rose-50',
                        'medium' => 'text-amber-600 border-amber-100 bg-amber-50',
                        default => 'text-indigo-600 border-indigo-100 bg-indigo-50'
                    };
                @endphp
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusColor }}">
                    {{ str_replace('_', ' ', $ticket->status) }}
                </span>
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $prioColor }}">
                    {{ $ticket->priority }} PRIORITY
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 animate-fade-in-down text-left">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-left">
                <!-- Primary Incident Content -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Ticket Source Data -->
                    <div class="bg-white rounded-3xl lg:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden text-left">
                        <div class="p-6 lg:p-10 space-y-4 lg:space-y-6">
                            <div class="space-y-2">
                                <h3 class="text-xl lg:text-3xl font-black text-gray-900 tracking-tighter uppercase leading-tight">{{ $ticket->subject }}</h3>
                                <div class="flex items-center gap-2 lg:gap-3">
                                    <span class="text-[9px] lg:text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 lg:px-3 py-1 rounded-lg">
                                        {{ $ticket->category->name }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="h-px bg-gray-50 w-full"></div>

                            <div class="prose prose-indigo max-w-none">
                                <p class="text-xs lg:text-sm font-bold text-gray-700 leading-relaxed uppercase tracking-tight italic text-gray-400 mb-2">Detailed Report:</p>
                                <div class="text-gray-900 font-bold text-xs lg:text-sm leading-relaxed whitespace-pre-line uppercase tracking-tight">
                                    {{ $ticket->description }}
                                </div>
                            </div>

                            @if($ticket->attachments->count() > 0)
                            <div class="mt-6 lg:mt-8 pt-6 lg:pt-8 border-t border-gray-50 flex flex-wrap gap-3 lg:gap-4 text-left">
                                @foreach($ticket->attachments as $attachment)
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex items-center gap-2 lg:gap-3 p-3 lg:p-4 bg-gray-50 rounded-xl lg:rounded-2xl border border-gray-100 hover:border-indigo-200 hover:bg-white transition-all group">
                                        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-white rounded-lg lg:rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-indigo-500 transition-colors">
                                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-[8px] lg:text-[9px] font-black text-gray-400 uppercase tracking-widest group-hover:text-indigo-400">Attached Asset</p>
                                            <p class="text-[9px] lg:text-[10px] font-black text-gray-900 uppercase tracking-tight">{{ Str::limit($attachment->file_name, 20) }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Conversation Protocol (Chat) -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 px-2">
                            <div class="h-px bg-gray-200 flex-1"></div>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Communication Ledger</h4>
                            <div class="h-px bg-gray-200 flex-1"></div>
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
                                <div class="flex {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[90%] lg:max-w-[85%] {{ $colorClass }} rounded-2xl lg:rounded-[2rem] rounded-tl-none {{ $isSelf ? 'rounded-tl-2xl lg:rounded-[2rem] rounded-tr-none' : '' }} p-4 lg:p-6 shadow-lg">
                                        <div class="flex items-center justify-between gap-4 lg:gap-8 mb-2">
                                            <span class="text-[8px] lg:text-[9px] font-black uppercase tracking-widest opacity-90">
                                                {{ $response->user->name }} @if($role !== 'user') ({{ strtoupper($role) }}) @endif
                                            </span>
                                            <span class="text-[8px] lg:text-[9px] font-bold uppercase opacity-70">
                                                {{ $response->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] lg:text-[11px] font-black uppercase tracking-tight leading-relaxed">
                                            {!! nl2br(e($response->message)) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white border border-gray-100 rounded-3xl p-10 text-center shadow-sm">
                                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Awaiting administrative response protocol</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Action Layer -->
                    @if($ticket->status != 'closed' && $ticket->status != 'resolved')
                        <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/30 flex justify-between items-center text-left">
                                <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Respond to Transmission</h4>
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">Uplink Stable</span>
                                </div>
                            </div>
                            <div class="p-8">
                                <form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="message" rows="4" 
                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700/80 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all uppercase tracking-tight placeholder:italic" 
                                        placeholder="ENTER RESPONSE DATA..." required></textarea>
                                    <div class="mt-6 flex justify-end">
                                        <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all transform hover:-translate-y-1 active:translate-y-0 flex items-center gap-3 text-left">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                            Post Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        @if($ticket->status == 'closed')
                            <div class="bg-slate-900 rounded-[2.5rem] p-10 text-center shadow-2xl text-left mb-8">
                                <h4 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] mb-2">Protocol Status: Terminated</h4>
                                <p class="text-xs font-black text-slate-300 uppercase tracking-widest leading-relaxed">This ledger has been formally closed and is now read-only for archival integrity purposes.</p>
                            </div>
                        @endif

                        <!-- Resolution Feedback Protocol -->
                        @if($ticket->status == 'resolved' || ($ticket->status == 'closed' && $ticket->feedback))
                            <!-- Desktop View -->
                            <div class="hidden lg:block bg-indigo-600 rounded-[2.5rem] shadow-2xl p-10 text-white relative overflow-hidden text-center">
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                                <div class="relative z-10">
                                    <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-[0.3em] mb-4">Service Finalization Alpha</h4>
                                    <div class="inline-flex items-center gap-3 px-6 py-2 bg-white/10 rounded-full border border-white/20 mb-8">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-white">Status: {{ strtoupper($ticket->status) }}</p>
                                    </div>
                                    
                                    @if(!$ticket->feedback && $ticket->status == 'resolved')
                                        <form action="{{ route('tickets.feedback', $ticket->id) }}" method="POST" x-data="{ rating: 5 }" class="space-y-8">
                                            @csrf
                                            <input type="hidden" name="rating" :value="rating">
                                            
                                            <div class="flex justify-center gap-4">
                                                <template x-for="i in 5">
                                                    <button type="button" @click="rating = i" class="focus:outline-none transition-transform hover:scale-125 group relative">
                                                        <svg class="w-16 h-16 transition-all" :class="i <= rating ? 'text-amber-400 drop-shadow-[0_0_15px_rgba(251,191,36,0.5)]' : 'text-indigo-400/50'" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                        <div x-show="rating == i" class="absolute -bottom-4 left-1/2 -translate-x-1/2 text-[10px] font-black text-rose-300 animate-bounce uppercase tracking-widest">
                                                            Select
                                                        </div>
                                                    </button>
                                                </template>
                                            </div>

                                            <div class="max-w-xl mx-auto">
                                                <textarea name="comment" 
                                                    class="w-full bg-white/10 border-white/20 rounded-[2rem] py-6 px-10 text-sm font-black text-white uppercase tracking-tight placeholder:text-indigo-300 focus:bg-white/20 focus:ring-0 focus:border-white transition-all text-center" 
                                                    rows="3" placeholder="PROVIDE FINAL SERVICE REVIEW..."></textarea>
                                            </div>
                                            
                                            <button type="submit" class="bg-white text-indigo-700 font-black py-4 px-12 rounded-2xl text-[10px] uppercase tracking-[0.3em] shadow-2xl hover:bg-gray-100 transition-all transform hover:-translate-y-1 active:scale-95">
                                                Deploy Feedback Protocol
                                            </button>
                                        </form>
                                    @elseif($ticket->feedback)
                                        <div class="bg-white/10 p-10 rounded-[2.5rem] border border-white/20 backdrop-blur-sm max-w-xl mx-auto shadow-2xl">
                                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-4">Post-Service Audit Complete</p>
                                            <div class="flex justify-center gap-1 mb-6">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-8 h-8 {{ $i <= $ticket->feedback->rating ? 'text-amber-400' : 'text-indigo-400/50' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            @if($ticket->feedback->comment)
                                                <div class="bg-indigo-900/30 p-6 rounded-2xl border border-white/10 text-center">
                                                    <p class="text-xs font-bold text-indigo-100 italic uppercase tracking-tight">"{{ $ticket->feedback->comment }}"</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Mobile View Handled in Sidebar Area -->
                        @endif
                    @endif
                </div>

                <!-- Secondary Meta-Data (Sidebar) -->
                <div class="lg:col-span-4 space-y-8 text-left">
                    <div class="hidden lg:block bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/10 text-left">
                            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Protocol Metadata</h4>
                        </div>
                        <div class="p-8 space-y-6">
                            <!-- System ID Hidden as requested -->
                            <!--
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">System Identifier</p>
                                <p class="text-[10px] font-black text-indigo-600 uppercase font-mono tracking-tighter">{{ $ticket->uuid }}</p>
                            </div>
                            -->
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Initialization Timestamp</p>
                                <p class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ $ticket->created_at->format('d M Y - H:i') }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest">Last Update Delta</p>
                                <p class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ strtoupper($ticket->updated_at->diffForHumans()) }}</p>
                            </div>
                            <div class="h-px bg-gray-50"></div>
                            <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl text-left">
                                <p class="text-[9px] font-black text-indigo-700 uppercase tracking-widest mb-1">Operational Support</p>
                                <p class="text-[10px] font-bold text-indigo-600/80 uppercase tracking-tight leading-relaxed">
                                    Our support operatives are assigned based on functional category specializations to ensure maximum throughput and quality.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile View: Side-by-Side Grid (Service Eval + Metadata) -->
                    <div class="lg:hidden grid {{ ($ticket->status == 'resolved' || ($ticket->status == 'closed' && $ticket->feedback)) ? 'grid-cols-2' : 'grid-cols-1' }} gap-3">
                        
                        @if($ticket->status == 'resolved' || ($ticket->status == 'closed' && $ticket->feedback))
                        <!-- Item 1: Service Finalization (Square/Rectangle) -->
                        <div class="bg-indigo-600 rounded-2xl p-4 text-white text-center shadow-lg flex flex-col justify-center items-center h-auto min-h-[10rem] relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 w-20 h-20 bg-white/10 rounded-full blur-xl"></div>
                            
                            <h4 class="text-[8px] font-black text-indigo-200 uppercase tracking-widest mb-2 relative z-10">Service Alpha</h4>
                            
                            @if(!$ticket->feedback && $ticket->status == 'resolved')
                                <form action="{{ route('tickets.feedback', $ticket->id) }}" method="POST" x-data="{ rating: 5 }" class="w-full relative z-10">
                                    @csrf
                                    <input type="hidden" name="rating" :value="rating">
                                    <div class="flex justify-center gap-1 mb-2">
                                        <template x-for="i in 5">
                                            <button type="button" @click="rating = i" class="focus:outline-none transition-transform hover:scale-110">
                                                <svg class="w-5 h-5" :class="i <= rating ? 'text-amber-400 drop-shadow-sm' : 'text-indigo-400/50'" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                    <textarea name="comment" rows="2" class="w-full bg-white/10 border-white/20 rounded-lg py-1.5 px-3 text-[9px] font-bold text-white uppercase tracking-tight placeholder:text-indigo-300 focus:bg-white/20 focus:ring-0 focus:border-white transition-all mb-2" placeholder="COMMENT..."></textarea>
                                    <button type="submit" class="w-full bg-white text-indigo-700 font-black py-1.5 rounded-lg text-[7px] uppercase tracking-widest hover:bg-gray-100 transition-all">Submit</button>
                                </form>
                            @else
                                <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-sm w-full relative z-10 text-center">
                                    <p class="text-[7px] font-black text-indigo-200 uppercase tracking-widest mb-1">Audit Done</p>
                                    <div class="flex justify-center gap-0.5 mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 {{ $i <= $ticket->feedback->rating ? 'text-amber-400' : 'text-indigo-400/50' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    @if($ticket->feedback->comment)
                                        <p class="text-[7px] font-bold text-indigo-100 italic uppercase tracking-tight leading-tight line-clamp-2">"{{ $ticket->feedback->comment }}"</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($ticket->status == 'resolved' && !$ticket->feedback)
                            <script>
                                // Adjusting second grid item if feedback is being filled
                                document.addEventListener('DOMContentLoaded', function() {
                                    const metadataCard = document.querySelector('.lg\\:hidden.grid > div:last-child');
                                    if(metadataCard) metadataCard.classList.remove('h-40');
                                    if(metadataCard) metadataCard.classList.add('h-auto', 'min-h-[10rem]');
                                });
                            </script>
                        @endif
                        @endif

                        <!-- Item 2: Protocol Metadata (Square) -->
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col justify-center h-40 relative overflow-hidden text-left">
                            <h4 class="text-[8px] font-black text-gray-900 uppercase tracking-widest mb-3">Protocol Data</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest">Init Time</p>
                                    <p class="text-[9px] font-black text-indigo-600 uppercase">{{ $ticket->created_at->format('d M H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-[7px] font-black text-gray-400 uppercase tracking-widest">Delta</p>
                                    <p class="text-[9px] font-black text-gray-900 uppercase">{{ strtoupper($ticket->updated_at->shortAbsoluteDiffForHumans()) }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- PINS Assurance -->
                    <!-- PINS Assurance -->
                    <div class="bg-indigo-600 rounded-2xl lg:rounded-3xl p-6 lg:p-8 shadow-xl shadow-indigo-100 relative group overflow-hidden text-left">
                        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                        <h4 class="text-[9px] lg:text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-3 lg:mb-4 text-left">Service Guarantee</h4>
                        <p class="text-[9px] lg:text-[10px] font-bold text-white leading-relaxed uppercase tracking-tight text-left">
                            PINS IT Services guarantees data integrity and professional operational response for every submitted protocol within our global ecosystem.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
