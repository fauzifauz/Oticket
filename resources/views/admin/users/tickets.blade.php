<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Tickets for ') . $user->name }}
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Complete history of user submissions</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-extrabold text-gray-800 tracking-tight uppercase text-xs">Submission Ledger</h3>
                    <div class="px-3 py-1 bg-white border border-gray-100 rounded-full text-[10px] font-bold text-gray-500 uppercase tracking-widest shadow-sm">
                        TOTAL: {{ count($user->tickets) }}
                    </div>
                </div>

                <div class="overflow-x-auto text-left">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Ticket ID</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Subject & Dept</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Last Activity</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Assignee</th>
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 uppercase tracking-tight">
                            @forelse($user->tickets as $ticket)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150 group text-xs font-bold text-gray-700">
                                <td class="px-8 py-5">
                                    <span class="font-mono text-gray-400 text-[10px]">#{{ strtoupper(substr($ticket->uuid, 0, 8)) }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="font-extrabold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $ticket->subject }}</p>
                                    <p class="text-[9px] text-gray-400 mt-0.5 tracking-widest">{{ $user->department ?? 'GENERAL' }}</p>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-widest 
                                        {{ $ticket->status == 'open' ? 'bg-rose-50 text-rose-600' : 
                                           ($ticket->status == 'in_progress' ? 'bg-amber-50 text-amber-600' : 
                                           ($ticket->status == 'resolved' ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-50 text-gray-500')) }}">
                                        {{ str_replace('_', ' ', $ticket->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 font-medium text-gray-500 lowercase tracking-normal">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-5">
                                    @if($ticket->support)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-md bg-indigo-50 flex items-center justify-center text-indigo-600 text-[8px] font-black uppercase">
                                                {{ substr($ticket->support->name, 0, 2) }}
                                            </div>
                                            <span class="text-[10px] font-bold text-gray-600">{{ $ticket->support->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-300 italic tracking-widest">UNASSIGNED</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition duration-150">
                                        Open Record
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">No tickets archived for this profile</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
