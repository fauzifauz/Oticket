<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center text-left">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Support Dashboard') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Performance overview and active assignments</p>
            </div>
            <div>
                <a href="{{ route('support.tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition duration-150">
                    My Tickets
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-2 md:gap-6 mb-4 md:mb-8 text-left">
                <!-- Assigned -->
                <div class="bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4 group hover:shadow-md transition-all">
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-indigo-50 rounded-lg md:rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <p class="text-[7px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Assigned Tickets</p>
                        <p class="text-lg md:text-2xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $stats['assignedTickets'] }}</p>
                    </div>
                </div>

                <!-- Open -->
                <div class="bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4 group hover:shadow-md transition-all">
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-rose-50 rounded-lg md:rounded-xl flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[7px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Active Open</p>
                        <p class="text-lg md:text-2xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $stats['openTickets'] }}</p>
                    </div>
                </div>

                <!-- Resolved -->
                <div class="bg-white p-3 md:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4 group hover:shadow-md transition-all">
                    <div class="w-8 h-8 md:w-12 md:h-12 bg-emerald-50 rounded-lg md:rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[7px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-tight">Resolved Tasks</p>
                        <p class="text-lg md:text-2xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $stats['resolvedTickets'] }}</p>
                    </div>
                </div>
            </div>

            <!-- SLA Warning Card + Quick Actions Row -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 text-left">
                <!-- SLA Warnings Card -->
                <div class="lg:col-span-2 bg-gradient-to-br from-rose-50 to-amber-50 p-4 md:p-6 rounded-xl md:rounded-2xl shadow-sm border border-rose-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-rose-200/20 rounded-full -mr-16 -mt-16"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3 mb-3 md:mb-4">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-rose-500 rounded-lg md:rounded-xl flex items-center justify-center text-white">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-[10px] md:text-xs font-black text-gray-900 uppercase tracking-widest">SLA Alerts</h3>
                                <p class="text-[8px] md:text-[9px] text-gray-500 font-bold uppercase tracking-tighter">Critical deadline monitoring</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 md:gap-4">
                            <a href="{{ route('support.tickets.index') }}?overdue=1" class="bg-white/80 backdrop-blur-sm p-3 md:p-4 rounded-xl border border-rose-200 hover:bg-rose-50 transition-colors group cursor-pointer">
                                <div class="flex items-center gap-1.5 md:gap-2 mb-1">
                                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                    <p class="text-[7px] md:text-[9px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-rose-600 transition-colors">Overdue</p>
                                </div>
                                <p class="text-lg md:text-2xl font-extrabold text-rose-600">{{ $slaWarnings['overdue'] }}</p>
                                <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter mt-1 group-hover:text-rose-400">Tickets past SLA</p>
                            </a>
                            <a href="{{ route('support.tickets.index') }}?sla_warning=1" class="bg-white/80 backdrop-blur-sm p-3 md:p-4 rounded-xl border border-amber-200 hover:bg-amber-50 transition-colors group cursor-pointer">
                                <div class="flex items-center gap-1.5 md:gap-2 mb-1">
                                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    <p class="text-[7px] md:text-[9px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-amber-600 transition-colors">Due Soon</p>
                                </div>
                                <p class="text-lg md:text-2xl font-extrabold text-amber-600">{{ $slaWarnings['dueSoon'] }}</p>
                                <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter mt-1 group-hover:text-amber-400">Within 2 hours</p>
                            </a>
                        </div>
                        @if($slaWarnings['overdue'] > 0 || $slaWarnings['dueSoon'] > 0)
                            <a href="{{ route('support.tickets.index') }}?overdue=1" class="mt-3 md:mt-4 inline-flex items-center px-3 md:px-4 py-1.5 md:py-2 bg-rose-600 text-white rounded-lg md:rounded-xl text-[8px] md:text-[10px] font-black uppercase tracking-widest hover:bg-rose-700 shadow-lg shadow-rose-100 transition-all">
                                <svg class="w-3 h-3 md:w-4 md:h-4 mr-1.5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                View Critical Tickets
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Panel -->
                <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-3 md:mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Quick Actions
                    </h3>
                    <div class="grid grid-cols-3 md:grid-cols-1 gap-2 md:gap-0 md:space-y-2">
                        <a href="{{ route('support.tickets.index') }}?status=open" class="flex flex-col md:flex-row items-center md:gap-3 p-2 md:p-3 bg-gray-50 hover:bg-indigo-50 rounded-xl transition-all group border border-transparent hover:border-indigo-100 text-center md:text-left">
                            <div class="w-8 h-8 md:w-8 md:h-8 bg-indigo-100 group-hover:bg-indigo-600 rounded-lg flex items-center justify-center text-indigo-600 group-hover:text-white transition-colors mx-auto md:mx-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1 mt-1 md:mt-0">
                                <p class="text-[8px] md:text-[10px] font-black text-gray-900 uppercase tracking-tight leading-none">Open</p>
                                <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter leading-none hidden md:block">Active queue</p>
                            </div>
                            <span class="text-[9px] md:text-xs font-black text-indigo-600 mt-0.5 md:mt-0">{{ $stats['openTickets'] }}</span>
                        </a>
                        <a href="{{ route('support.tickets.index') }}?status=in_progress" class="flex flex-col md:flex-row items-center md:gap-3 p-2 md:p-3 bg-gray-50 hover:bg-amber-50 rounded-xl transition-all group border border-transparent hover:border-amber-100 text-center md:text-left">
                            <div class="w-8 h-8 md:w-8 md:h-8 bg-amber-100 group-hover:bg-amber-600 rounded-lg flex items-center justify-center text-amber-600 group-hover:text-white transition-colors mx-auto md:mx-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="flex-1 mt-1 md:mt-0">
                                <p class="text-[8px] md:text-[10px] font-black text-gray-900 uppercase tracking-tight leading-none">In Prog</p>
                                <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter leading-none hidden md:block">Working on</p>
                            </div>
                            <span class="text-[9px] md:text-xs font-black text-amber-600 mt-0.5 md:mt-0">{{ $stats['inProgressTickets'] }}</span>
                        </a>
                        <a href="{{ route('support.tickets.index') }}" class="flex flex-col md:flex-row items-center md:gap-3 p-2 md:p-3 bg-gray-50 hover:bg-emerald-50 rounded-xl transition-all group border border-transparent hover:border-emerald-100 text-center md:text-left">
                            <div class="w-8 h-8 md:w-8 md:h-8 bg-emerald-100 group-hover:bg-emerald-600 rounded-lg flex items-center justify-center text-emerald-600 group-hover:text-white transition-colors mx-auto md:mx-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div class="flex-1 mt-1 md:mt-0">
                                <p class="text-[8px] md:text-[10px] font-black text-gray-900 uppercase tracking-tight leading-none">All</p>
                                <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter leading-none hidden md:block">Full queue</p>
                            </div>
                            <span class="text-[9px] md:text-xs font-black text-emerald-600 mt-0.5 md:mt-0">{{ $stats['assignedTickets'] }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics Row -->
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-2 lg:gap-6 mb-4 lg:mb-8 text-left">
                <!-- MY Avg Response Time -->
                <div class="bg-white p-3 lg:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex flex-col xl:flex-row xl:items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-50 rounded-lg md:rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">My Response</p>
                            <p class="text-lg md:text-xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $performance['myAvgResponseTime'] }}<span class="text-[10px] md:text-sm text-gray-400 ml-1">min</span></p>
                        </div>
                    </div>
                    <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ min(100, (10 - min(10, $performance['myAvgResponseTime'])) * 10) }}%"></div>
                    </div>
                    <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter mt-1 md:mt-2">Personal avg</p>
                </div>

                <!-- TEAM Avg Response Time -->
                <div class="bg-white p-3 lg:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex flex-col xl:flex-row xl:items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-cyan-50 rounded-lg md:rounded-xl flex items-center justify-center text-cyan-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Team Response</p>
                            <p class="text-lg md:text-xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $performance['teamAvgResponseTime'] }}<span class="text-[10px] md:text-sm text-gray-400 ml-1">min</span></p>
                        </div>
                    </div>
                    <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-cyan-500 rounded-full" style="width: {{ min(100, (10 - min(10, $performance['teamAvgResponseTime'])) * 10) }}%"></div>
                    </div>
                    <p class="text-[7px] md:text-[8px] text-gray-400 font-bold uppercase tracking-tighter mt-1 md:mt-2">Overall avg</p>
                </div>

                <!-- Resolution Rate -->
                <div class="bg-white p-3 lg:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex flex-col xl:flex-row xl:items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-50 rounded-lg md:rounded-xl flex items-center justify-center text-emerald-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Resolution Rate</p>
                            <p class="text-lg md:text-xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $performance['resolutionRate'] }}<span class="text-[10px] md:text-sm text-gray-400 ml-1">%</span></p>
                        </div>
                    </div>
                    <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $performance['resolutionRate'] }}%"></div>
                    </div>
                </div>

                <!-- Avg Resolution Time -->
                <div class="bg-white p-3 lg:p-6 rounded-xl md:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex flex-col xl:flex-row xl:items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-50 rounded-lg md:rounded-xl flex items-center justify-center text-purple-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-bold text-gray-400 uppercase tracking-widest leading-none">Avg Resolution</p>
                            <p class="text-lg md:text-xl font-extrabold text-gray-900 leading-none mt-0.5 md:mt-0">{{ $performance['avgResolutionTime'] }}<span class="text-[10px] md:text-sm text-gray-400 ml-1">hrs</span></p>
                        </div>
                    </div>
                    <div class="h-1 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: {{ min(100, (24 - min(24, $performance['avgResolutionTime'])) * 4) }}%"></div>
                    </div>
                </div>

                <!-- Today's Resolved -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-3 lg:p-6 rounded-xl md:rounded-2xl shadow-lg shadow-indigo-100 text-white hover:shadow-xl transition-all">
                    <div class="flex flex-col xl:flex-row xl:items-center gap-2 md:gap-3 mb-2 md:mb-3 text-left">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-white/20 rounded-lg md:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[7px] md:text-[9px] font-bold text-indigo-100 uppercase tracking-widest leading-none">Today Resolved</p>
                            <p class="text-xl md:text-2xl font-extrabold leading-none mt-0.5 md:mt-0">{{ $performance['todayResolved'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Global Satisfaction & Feedback Ticker -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 lg:gap-6 mb-8 text-left">
                <!-- Rating Satisfaction Card -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-4 lg:p-6 rounded-2xl shadow-lg shadow-orange-100 text-white hover:shadow-xl transition-all">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-orange-50 uppercase tracking-widest leading-none">Global Satisfaction</p>
                            <p class="text-2xl font-black leading-none mt-1">{{ number_format($performance['avgRating'], 1) }}<span class="text-sm opacity-80 ml-1">/ 5.0</span></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[9px] text-orange-50 font-black uppercase tracking-widest">From {{ $performance['totalRatings'] }} Reviews</p>
                        <div class="flex gap-px">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= round($performance['avgRating']) ? 'text-white' : 'text-white/30' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Feedback History Scrolling Ticker -->
                <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative flex flex-col justify-center py-4">
                    <div class="px-6 mb-2 flex items-center justify-between">
                        <h4 class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-ping"></span>
                            Live Feedback Protocol
                        </h4>
                        <span class="text-[8px] font-black text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full uppercase tracking-widest">Real-time Feed</span>
                    </div>
                    
                    <style>
                        @keyframes scroll {
                            0% { transform: translateX(0); }
                            100% { transform: translateX(-50%); }
                        }
                        .ticker-wrapper {
                            display: flex;
                            width: fit-content;
                            animation: scroll 40s linear infinite;
                        }
                        .ticker-wrapper:hover {
                            animation-play-state: paused;
                        }
                    </style>

                    <div class="relative overflow-hidden w-full group">
                        <div class="ticker-wrapper flex gap-4 px-6">
                            @php
                                // Duplicate items for infinite effect
                                $tickerItems = $recentFeedbacks->concat($recentFeedbacks);
                            @endphp
                            @forelse($tickerItems as $feedback)
                                <a href="{{ route('support.tickets.show', $feedback->id) }}" class="flex-shrink-0 bg-gray-50/50 border border-gray-100 p-4 rounded-xl min-w-[300px] max-w-[400px] flex items-start gap-4 hover:shadow-md hover:border-indigo-100 transition-all cursor-pointer">
                                    <div class="w-10 h-10 bg-white rounded-lg shadow-sm flex flex-col items-center justify-center shrink-0 border border-gray-100">
                                        <p class="text-[14px] font-black text-indigo-600 leading-none">{{ $feedback->feedback->rating }}</p>
                                        <div class="flex gap-px mt-0.5">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-1.5 h-1.5 {{ $i <= $feedback->feedback->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <div class="flex justify-between items-center mb-1">
                                            <p class="text-[9px] font-black text-gray-900 uppercase truncate">{{ $feedback->user->name }}</p>
                                            <p class="text-[8px] font-bold text-gray-400 uppercase">{{ $feedback->updated_at->shortRelativeDiffForHumans() }}</p>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-600 uppercase tracking-tight italic line-clamp-2 leading-relaxed">&quot;{{ $feedback->feedback->comment ?: 'NO VERBAL FEEDBACK PROVIDED' }}&quot;</p>
                                    </div>
                                </a>
                            @empty
                                <div class="w-full text-center py-2">
                                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Initializing Feedback Pulse...</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tickets Table -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Recent Assigned Tickets
                        </h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Latest updates from your queue</p>
                    </div>
                    <a href="{{ route('support.tickets.index') }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest transition-colors">
                        View Full Queue &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-center">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-left">Subject</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Priority</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Last Updated</th>
                                <th class="px-8 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 uppercase tracking-tight">
                            @forelse($recentTickets as $ticket)
                            <tr class="hover:bg-gray-50/70 transition-all duration-200 group text-[11px] font-bold text-gray-700">
                                <td class="px-8 py-5 text-left">
                                    <div class="flex flex-col gap-0.5">
                                        <p class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors truncate max-w-[300px]">{{ $ticket->subject }}</p>
                                        <p class="text-[9px] text-gray-400 tracking-widest">#{{ strtoupper(substr($ticket->uuid, 0, 8)) }} &bull; {{ $ticket->category->name }}</p>
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
                                <td class="px-4 py-5 font-bold">
                                    <div class="flex justify-center">
                                        <span class="flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-[9px] font-black uppercase tracking-tighter border border-gray-200 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $ticket->status == 'open' ? 'bg-rose-500' : ($ticket->status == 'in_progress' ? 'bg-indigo-500' : 'bg-emerald-500') }} animate-pulse"></span>
                                            {{ str_replace('_', ' ', $ticket->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-gray-400 font-medium text-[10px]">
                                    {{ $ticket->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('support.tickets.show', $ticket->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 hover:border-indigo-100 shadow-sm transition-all">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                        </div>
                                        <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No active assignments in your queue</p>
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
