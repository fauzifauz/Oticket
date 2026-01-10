<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('System Activity Logs') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Comprehensive audit trail of all administrative and operational actions</p>
            </div>
            <div>
                @if($logs->total() > 0)
                <form action="{{ route('admin.activity_logs.clear') }}" method="POST" onsubmit="return confirm('CRITICAL WARNING: You are about to PERMANENTLY WIPE the entire system audit trail. This action is irreversible. Proceed?');">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-2.5 bg-white border border-rose-100 text-rose-600 rounded-xl text-[9px] sm:text-[10px] font-black uppercase tracking-widest hover:bg-rose-50 transition-all duration-200 shadow-sm shadow-rose-50 w-full sm:w-auto justify-center">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Clear Audit Trail
                    </button>
                </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 animate-fade-in-down text-left">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-gray-100 bg-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-[10px] sm:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-indigo-500"></span>
                            Detailed Audit Ledger
                        </h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Real-time surveillance of system events and state mutations</p>
                    </div>
                    <div class="w-full md:w-auto">
                        <form action="{{ route('admin.activity_logs.index') }}" method="GET" class="grid grid-cols-2 md:flex md:items-center gap-2 sm:gap-3 w-full">
                            <div class="col-span-2 md:w-64">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs or agents..." 
                                        class="w-full py-2 pl-9 pr-3 bg-gray-50/50 border border-gray-100 rounded-xl text-[10px] sm:text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none">
                                </div>
                            </div>
                            <div class="col-span-1">
                                <select name="user_id" class="w-full py-2 px-3 bg-gray-50/50 border border-gray-100 rounded-xl text-[10px] sm:text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none cursor-pointer" onchange="this.form.submit()">
                                    <option value="">All Agents</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <select name="action" class="w-full py-2 px-3 bg-gray-50/50 border border-gray-100 rounded-xl text-[10px] sm:text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none cursor-pointer" onchange="this.form.submit()">
                                    <option value="">All Protocols</option>
                                    @foreach($actions as $act)
                                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ strtoupper(str_replace('_', ' ', $act)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2 md:col-span-1 flex items-center justify-between md:justify-start gap-2">
                                <button type="submit" class="hidden md:inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition-all active:scale-95">
                                    Filter
                                </button>
                                <div class="px-3 py-2 bg-indigo-50/50 border border-indigo-100/50 rounded-xl flex items-center gap-1.5 shrink-0 ml-auto md:ml-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                    <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest">
                                        Total: {{ $logs->total() }}
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto text-left">
                    <table class="w-full text-center">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-left">Timestamp (UTC+7)</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-left">Operative Agent</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Action Protocol</th>
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-left">Event Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 uppercase tracking-tight text-left">
                            @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/70 transition-all duration-200 group text-[11px] font-bold text-gray-700">
                                <td class="px-8 py-5 text-left whitespace-nowrap">
                                    <p class="font-black text-gray-900">{{ $log->created_at->format('Y-m-d') }}</p>
                                    <p class="text-[9px] text-gray-400 tracking-widest mt-0.5">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-4 py-5 text-left">
                                    <div class="flex flex-col">
                                        <p class="font-black text-gray-900 uppercase">{{ $log->user->name }}</p>
                                        <p class="text-[9px] text-gray-400 lowercase tracking-tight">{{ $log->user->email }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-5 font-bold">
                                    <div class="flex justify-center">
                                        <span class="px-3 py-1 bg-gray-900 text-gray-100 rounded-lg text-[9px] font-black tracking-widest shadow-lg shadow-gray-200">
                                            {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-left">
                                    <div class="prose prose-sm max-w-[400px]">
                                        <p class="text-[11px] font-bold text-gray-600 leading-relaxed uppercase tracking-tight">
                                            {{ $log->description }}
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-200">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">Audit Ledger is currently vacant</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 text-left">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
