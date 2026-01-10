<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-left gap-3 sm:gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight leading-tight">
                    {{ __('Command Center') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest leading-none">Real-time analytical dashboard</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-3">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] font-black uppercase tracking-widest animate-pulse">
                    Live Active
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Context Filter Bar -->
            <div class="bg-white p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="p-1.5 sm:p-2 bg-indigo-50 rounded-lg">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">Analytical Context</h3>
                            <p class="text-[8px] sm:text-[10px] font-medium text-gray-500 uppercase tracking-widest">Temporal data slicing</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.dashboard') }}" method="GET" class="w-full lg:w-auto">
                        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-3">
                            <select name="year" onchange="this.form.submit()" class="h-8 sm:h-9 px-2 sm:px-3 py-0 bg-gray-50 border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold text-gray-700 focus:ring-indigo-500/20 focus:border-indigo-500">
                                <option value="">All Years</option>
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>

                            <input type="month" name="month" value="{{ request('month') }}" onchange="this.form.submit()"
                                   class="h-8 sm:h-9 px-2 sm:px-3 py-0 bg-gray-50 border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold text-gray-700 focus:ring-indigo-500/20 focus:border-indigo-500">

                            <input type="week" name="week" value="{{ request('week') }}" onchange="this.form.submit()"
                                   class="h-8 sm:h-9 px-2 sm:px-3 py-0 bg-gray-50 border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold text-gray-700 focus:ring-indigo-500/20 focus:border-indigo-500 col-span-1">

                            <a href="{{ route('admin.dashboard') }}" class="h-8 sm:h-9 px-3 sm:px-4 inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-[10px] sm:text-xs font-bold transition-all truncate">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Primary Impact Metrics -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 lg:gap-6 text-left">
                <a href="{{ route('admin.tickets.index', request()->only(['year', 'month', 'week'])) }}" class="group relative bg-white border border-gray-100 p-3 lg:p-6 rounded-xl shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-300 overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 md:w-20 md:h-20 bg-indigo-50 rounded-full blur-2xl group-hover:bg-indigo-100 transition-all"></div>
                    <p class="text-[7px] lg:text-xs font-black text-gray-400 uppercase tracking-widest mb-0.5 lg:mb-1 leading-tight">Total Incident Volume</p>
                    <p class="text-lg lg:text-4xl font-black text-gray-900 tracking-tighter group-hover:text-indigo-600 transition-colors">{{ $totalTickets }}</p>
                    <div class="mt-1 lg:mt-3 flex items-center gap-1">
                        <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-indigo-500"></span>
                        <p class="text-[6px] lg:text-[10px] font-black text-indigo-500 uppercase tracking-widest hidden lg:block">System aggregate</p>
                    </div>
                </a>

                <a href="{{ route('admin.tickets.index', array_merge(['priority' => 'high', 'is_unresolved' => 1], request()->only(['year', 'month', 'week']))) }}" class="group relative bg-white border border-gray-100 p-3 lg:p-6 rounded-xl shadow-sm hover:shadow-2xl hover:shadow-rose-100 transition-all duration-300 overflow-hidden text-left">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 md:w-20 md:h-20 bg-rose-50 rounded-full blur-2xl group-hover:bg-rose-100 transition-all"></div>
                    <p class="text-[7px] lg:text-xs font-black text-gray-400 uppercase tracking-widest mb-0.5 lg:mb-1 leading-tight">High-Priority Unresolved</p>
                    <p class="text-lg lg:text-4xl font-black text-gray-900 tracking-tighter group-hover:text-rose-600 transition-colors">{{ $highPriorityUnresolved }}</p>
                    <div class="mt-1 lg:mt-3 flex items-center gap-1">
                        <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-rose-500"></span>
                        <p class="text-[6px] lg:text-[10px] font-black text-rose-500 uppercase tracking-widest hidden lg:block">Action required</p>
                    </div>
                </a>

                <a href="{{ route('admin.analytics.index', request()->only(['year', 'month', 'week'])) }}" class="group relative bg-white border border-gray-100 p-3 lg:p-6 rounded-xl shadow-sm hover:shadow-2xl hover:shadow-yellow-100 transition-all duration-300 overflow-hidden text-left">
                    <div class="absolute -right-4 -bottom-4 w-16 h-16 md:w-20 md:h-20 bg-yellow-50 rounded-full blur-2xl group-hover:bg-yellow-100 transition-all"></div>
                    <p class="text-[7px] lg:text-xs font-black text-gray-400 uppercase tracking-widest mb-0.5 lg:mb-1 leading-tight">Support Integrity Rating</p>
                    <p class="text-lg lg:text-4xl font-black text-gray-900 tracking-tighter group-hover:text-yellow-600 transition-colors">{{ number_format($avgRating, 1) }}</p>
                    <div class="mt-1 lg:mt-3 flex items-center gap-1">
                        <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-yellow-500"></span>
                        <p class="text-[6px] lg:text-[10px] font-black text-yellow-600 uppercase tracking-widest hidden lg:block">Customer satisfaction</p>
                    </div>
                </a>

                <a href="{{ route('admin.tickets.index', array_merge(['overdue' => 1], request()->only(['year', 'month', 'week']))) }}" class="group relative bg-gray-900 p-3 lg:p-6 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden text-left">
                    <div class="absolute -right-4 -top-4 w-16 h-16 md:w-20 md:h-20 bg-rose-500/20 rounded-full blur-2xl group-hover:bg-rose-500/30 transition-all"></div>
                    <p class="text-[7px] lg:text-xs font-black text-rose-300/50 uppercase tracking-widest mb-0.5 lg:mb-1 leading-tight">SLA Violation Alert</p>
                    <p class="text-lg lg:text-4xl font-black text-white tracking-tighter">{{ $overdueTickets }}</p>
                    <div class="mt-1 lg:mt-3 flex items-center gap-1">
                        <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                        <p class="text-[6px] lg:text-[10px] font-black text-rose-400 uppercase tracking-widest hidden lg:block">Critical thresholds</p>
                    </div>
                </a>
            </div>

            <!-- Strategic Command Center (AI Evolution) -->
            @if($miningData && isset($miningData['departments']))
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 mb-8 relative overflow-hidden text-left">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -mr-16 -mt-16 blur-3xl opacity-50"></div>
                
                <div class="relative">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-600 rounded-lg shadow-lg shadow-blue-100">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-black text-gray-900 tracking-tight uppercase leading-none">Strategic Command Center</h2>
                                <p class="text-[10px] font-bold text-blue-600/60 uppercase tracking-widest mt-1">Departmental Statistics Overview</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-tighter">Status: Data Synchronized</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6">
                        <!-- Cluster Summary -->
                        <div class="lg:col-span-4 grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-3">
                            @foreach($miningData['clusters'] as $cluster)
                            <a href="{{ route('admin.tickets.index', array_merge(['departments' => $cluster['departments']], request()->only(['year', 'month', 'week']))) }}" 
                               class="group flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-white hover:shadow-md hover:ring-1 hover:ring-blue-100 transition-all duration-300">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $cluster['intensity'] == 'High' ? 'bg-rose-50 text-rose-600' : ($cluster['intensity'] == 'Medium' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none">{{ $cluster['intensity'] }} Activity</p>
                                        <p class="text-xs font-black text-gray-900 mt-1">{{ $cluster['count'] }} Sectors</p>
                                    </div>
                                </div>
                                <svg class="w-3 h-3 text-gray-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            @endforeach
                        </div>

                        <!-- Visualization -->
                        <div class="lg:col-span-8 bg-white rounded-xl border border-gray-100 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Departmental Load Intensity</h3>
                                <div class="flex gap-3">
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase">High</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase">Medium</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span class="text-[8px] font-black text-gray-400 uppercase">Low</span>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[200px] sm:h-[240px] relative">
                                <canvas id="miningClusterChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Department Insight Ledger -->
                    <div class="mt-6 rounded-xl border border-gray-100 overflow-hidden bg-white">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-100">Sector Analysis</th>
                                        <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-100">Load Factor</th>
                                        <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest border-r border-gray-100">Primary Domain</th>
                                        <th class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Activity Rating</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach(collect($miningData['departments'])->sortByDesc('report_count')->take(5) as $dept)
                                    <tr class="hover:bg-blue-50/30 transition-all group">
                                        <td class="px-5 py-3 border-r border-gray-100 cursor-pointer" onclick="window.location.href='{{ route('admin.tickets.index', array_merge(['department' => $dept['department']], request()->only(['year', 'month', 'week']))) }}'">
                                            <p class="text-[11px] font-black text-gray-900 uppercase tracking-tight group-hover:text-blue-600 transition-colors">{{ $dept['department'] }}</p>
                                        </td>
                                        <td class="px-5 py-3 border-r border-gray-100 cursor-pointer" onclick="window.location.href='{{ route('admin.tickets.index', array_merge(['department' => $dept['department']], request()->only(['year', 'month', 'week']))) }}'">
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                    @php $maxReports = collect($miningData['departments'])->max('report_count'); @endphp
                                                    <div class="h-full {{ $dept['intensity'] == 'High' ? 'bg-rose-500' : ($dept['intensity'] == 'Medium' ? 'bg-amber-500' : 'bg-emerald-500') }}" 
                                                         style="width: {{ ($dept['report_count'] / ($maxReports ?: 1)) * 100 }}%"></div>
                                                </div>
                                                <span class="text-[10px] font-black text-gray-900">{{ $dept['report_count'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 border-r border-gray-100 cursor-pointer" onclick="window.location.href='{{ route('admin.tickets.index', array_merge(['category_name' => $dept['top_category'], 'department' => $dept['department']], request()->only(['year', 'month', 'week']))) }}'">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(explode(', ', $dept['top_category']) as $cat)
                                                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full uppercase tracking-tighter">{{ $cat }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <div class="inline-flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full {{ $dept['intensity'] == 'High' ? 'bg-rose-500' : ($dept['intensity'] == 'Medium' ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                                                <span class="text-[9px] font-black uppercase tracking-widest {{ $dept['intensity'] == 'High' ? 'text-rose-600' : ($dept['intensity'] == 'Medium' ? 'text-amber-600' : 'text-emerald-600') }}">
                                                    {{ $dept['intensity'] }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 md:gap-6 text-left">
                <!-- Status Tracking Ledger -->
                <div class="lg:col-span-8 space-y-3 md:space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-white text-left">
                            <h3 class="text-[9px] md:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                Incident Lifecycle Tracking
                            </h3>
                        </div>
                        <div class="p-3 md:p-6 grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 text-left">
                            @php
                                $hoverClasses = [
                                    'gray' => 'hover:bg-gray-50 hover:border-gray-200 hover:text-gray-700',
                                    'rose' => 'hover:bg-rose-50 hover:border-rose-100 hover:text-rose-700',
                                    'indigo' => 'hover:bg-indigo-50 hover:border-indigo-100 hover:text-indigo-700',
                                    'emerald' => 'hover:bg-emerald-50 hover:border-emerald-100 hover:text-emerald-700',
                                    'amber' => 'hover:bg-amber-50 hover:border-amber-100 hover:text-amber-700',
                                    'blue' => 'hover:bg-blue-50 hover:border-blue-100 hover:text-blue-700',
                                    'violet' => 'hover:bg-violet-50 hover:border-violet-100 hover:text-violet-700',
                                ];
                                
                                $textClasses = [
                                    'gray' => 'group-hover:text-gray-600',
                                    'rose' => 'group-hover:text-rose-600',
                                    'indigo' => 'group-hover:text-indigo-600',
                                    'emerald' => 'group-hover:text-emerald-600',
                                    'amber' => 'group-hover:text-amber-600',
                                    'blue' => 'group-hover:text-blue-600',
                                    'violet' => 'group-hover:text-violet-600',
                                ];
                            @endphp
                            
                            @foreach($statusCounts as $status)
                                @php
                                    $color = $status->color ?? 'gray';
                                    $hClass = $hoverClasses[$color] ?? $hoverClasses['gray'];
                                    $tClass = $textClasses[$color] ?? $textClasses['gray'];
                                @endphp
                                <a href="{{ route('admin.tickets.index', array_merge(['status' => $status->slug], request()->only(['year', 'month', 'week']))) }}" class="group p-3 md:p-4 rounded-lg border border-gray-50 bg-gray-50/30 transition-all {{ $hClass }}">
                                    <p class="text-[7px] md:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5 md:mb-1 leading-none {{ $tClass }}">{{ $status->name }}</p>
                                    <p class="text-lg md:text-2xl font-black text-gray-900 leading-none">{{ $status->tickets_count }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Performance Analytics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-white text-left">
                            <h3 class="text-[9px] md:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                Performance Ledger
                            </h3>
                        </div>
                        <div class="p-3 lg:p-6 grid grid-cols-2 gap-2 lg:gap-4 text-left">
                            <div class="p-3 lg:p-6 rounded-lg bg-indigo-600 text-white shadow-xl shadow-indigo-100 text-left">
                                <p class="text-[7px] lg:text-[9px] font-black text-indigo-200 uppercase tracking-widest mb-1 lg:mb-1.5 leading-none">Avg Resolution Time</p>
                                <p class="text-base lg:text-3xl font-black tracking-tight leading-none">{{ $avgServiceTime }}</p>
                                <p class="text-[6px] lg:text-[8px] font-bold text-indigo-300 uppercase tracking-widest mt-1 lg:mt-3 leading-none hidden lg:block">Mean operational cycle</p>
                            </div>
                            <div class="p-3 lg:p-6 rounded-lg bg-indigo-50 border border-indigo-100 text-left">
                                <p class="text-[7px] lg:text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1 lg:mb-1.5 leading-none">Avg Initial Response</p>
                                <p class="text-base lg:text-3xl font-black text-indigo-700 tracking-tight leading-none">{{ $avgResponseTime }}</p>
                                <p class="text-[6px] lg:text-[8px] font-bold text-indigo-400 uppercase tracking-widest mt-1 lg:mt-3 leading-none hidden lg:block">Support efficiency metric</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Surveillance -->
                <div class="lg:col-span-4 grid grid-cols-2 lg:grid-cols-1 gap-2 lg:gap-4">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden text-left h-full">
                        <div class="hidden lg:block px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-white text-left">
                            <h3 class="text-[9px] md:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                Activity & Assignment
                            </h3>
                        </div>
                        <div class="p-2 lg:p-4 space-y-2 lg:space-y-3 h-full flex flex-col justify-center">
                            <!-- Admin Support Search -->
                            <form action="{{ route('admin.tickets.index') }}" method="GET" class="relative">
                                <input type="text" name="assigned_to" placeholder="Find Admin Support..." 
                                    class="w-full pl-2 lg:pl-3 pr-6 lg:pr-8 py-1.5 lg:py-2 bg-gray-50 border border-gray-200 rounded-lg text-[8px] lg:text-[10px] font-bold uppercase tracking-wide focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                <button type="submit" class="absolute right-2 top-1.5 lg:top-2 text-gray-400 hover:text-indigo-600">
                                    <svg class="w-3 h-3 lg:w-3.5 lg:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>

                            <a href="{{ route('admin.tickets.index', array_merge(['assigned' => 1], request()->only(['year', 'month', 'week']))) }}" class="flex items-center justify-between p-2 lg:p-4 bg-gray-50/50 rounded-lg lg:rounded-xl hover:bg-indigo-50 transition-all border border-gray-50 group">
                                <div>
                                    <p class="text-[6px] lg:text-[10px] font-black text-gray-400 group-hover:text-indigo-600 uppercase tracking-widest leading-none mb-1">Assigned Assets</p>
                                    <p class="text-base lg:text-xl font-black text-gray-900 leading-none">{{ $assignedTickets }}</p>
                                </div>
                                <svg class="w-4 h-4 lg:w-6 lg:h-6 text-gray-200 group-hover:text-indigo-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- System Status Card -->
                    <div class="bg-indigo-600 rounded-xl lg:rounded-3xl p-3 lg:p-8 shadow-xl shadow-indigo-100 overflow-hidden relative group text-left h-full flex flex-col justify-center">
                        <div class="absolute -right-4 -top-4 w-12 h-12 lg:w-24 lg:h-24 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                        <h4 class="text-[7px] lg:text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1.5 lg:mb-4 leading-none">Operative Overview</h4>
                        <p class="text-[8px] lg:text-xs font-bold text-white leading-tight lg:leading-relaxed uppercase tracking-tight">
                            Identity: Admin Registry <br>
                            Operatives: {{ $activeSupport }} <br>
                            Status: High Integrity
                        </p>
                        <div class="mt-2 lg:mt-6 flex items-center gap-1 lg:gap-2">
                            <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <p class="text-[6px] lg:text-[9px] font-black text-indigo-200 uppercase tracking-widest leading-none">Protocol Sync Nominal</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytical Visualizations -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 md:gap-8 text-left">
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                    <div class="px-4 sm:px-6 md:px-8 py-4 sm:py-5 md:py-6 border-b border-gray-100 bg-white">
                        <h3 class="text-[10px] sm:text-[11px] md:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-indigo-500"></span>
                            Status Distribution Matrix
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 md:p-6 lg:p-8">
                        <canvas id="statusChart" class="max-h-[180px] sm:max-h-[220px] md:max-h-[280px] lg:max-h-[300px]"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                    <div class="px-4 sm:px-6 md:px-8 py-4 sm:py-5 md:py-6 border-b border-gray-100 bg-white">
                        <h3 class="text-[10px] sm:text-[11px] md:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-indigo-500"></span>
                            Priority Impact Variance
                        </h3>
                    </div>
                    <div class="p-3 sm:p-4 md:p-6 lg:p-8">
                        <canvas id="priorityChart" class="max-h-[180px] sm:max-h-[220px] md:max-h-[280px] lg:max-h-[300px]"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                    <div class="px-8 py-6 border-b border-gray-100 bg-white">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Category Volume Density
                        </h3>
                    </div>
                    <div class="p-4 sm:p-8">
                        <canvas id="categoryChart" class="max-h-[220px] sm:max-h-[300px]"></canvas>
                    </div>
                </div>
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-left">
                    <div class="px-8 py-6 border-b border-gray-100 bg-white">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Incident Ingress Trend (Weekly)
                        </h3>
                    </div>
                    <div class="p-4 sm:p-8">
                        <canvas id="trendChart" class="max-h-[220px] sm:max-h-[300px]"></canvas>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Common Configuration
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.font.weight = 'bold';
                Chart.defaults.color = '#94a3b8';

                const tailwindColors = {
                    'gray': '#64748b',
                    'rose': '#f43f5e',
                    'indigo': '#6366f1',
                    'emerald': '#10b981',
                    'amber': '#f59e0b',
                    'blue': '#3b82f6',
                    'violet': '#8b5cf6'
                };

                const activeFilters = {
                    year: '{{ request('year') }}',
                    month: '{{ request('month') }}',
                    week: '{{ request('week') }}'
                };

                const getFilteredUrl = (baseUrl, params = {}) => {
                    const url = new URL(baseUrl, window.location.origin);
                    const allParams = { ...activeFilters, ...params };
                    Object.keys(allParams).forEach(key => {
                        if (allParams[key]) url.searchParams.set(key, allParams[key]);
                    });
                    return url.toString();
                };

                // Status Chart
                const statusLabels = {!! json_encode($ticketsByStatus->keys()) !!};
                const statusColors = {!! json_encode($statusColors) !!};
                const statusChartColors = statusLabels.map(l => tailwindColors[statusColors[l]] || tailwindColors['gray']);

                new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels.map(l => l.replace(['-', '_'], ' ').toUpperCase()),
                        datasets: [{
                            data: {!! json_encode($ticketsByStatus->values()) !!},
                            backgroundColor: statusChartColors,
                            borderWidth: 0,
                            hoverOffset: 20
                        }]
                    },
                    options: {
                        cutout: '75%',
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, padding: 20, font: { size: 9, weight: '900' } } } },
                        onClick: (e, activeEls) => {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = statusLabels[index];
                                window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { status: label });
                            }
                        }
                    }
                });

                // Priority Chart
                const priorityLabels = {!! json_encode($ticketsByPriority->keys()) !!};
                const priorityColorsMap = {!! json_encode($priorityColors) !!};
                const priorityChartColors = priorityLabels.map(l => tailwindColors[priorityColorsMap[l]] || tailwindColors['gray']);

                new Chart(document.getElementById('priorityChart'), {
                    type: 'bar',
                    data: {
                        labels: priorityLabels.map(l => l.toUpperCase()),
                        datasets: [{
                            label: 'INCIDENTS',
                            data: {!! json_encode($ticketsByPriority->values()) !!},
                            backgroundColor: priorityChartColors,
                            borderRadius: 12,
                            barThickness: 32
                        }]
                    },
                    options: {
                        scales: { 
                            y: { beginAtZero: true, grid: { display: false } },
                            x: { grid: { display: false } }
                        },
                        plugins: { legend: { display: false } },
                        onClick: (e, activeEls) => {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = priorityLabels[index];
                                window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { priority: label });
                            }
                        }
                    }
                });

                // Category Chart
                const categoryData = {!! json_encode($ticketsByCategory) !!};
                new Chart(document.getElementById('categoryChart'), {
                    type: 'pie',
                    data: {
                        labels: Object.keys(categoryData).map(k => k.toUpperCase()),
                        datasets: [{
                            data: Object.values(categoryData),
                            backgroundColor: [
                                '#818cf8', '#f472b6', '#6366f1', '#2dd4bf', '#fb923c', '#34d399', '#fbbf24'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, padding: 20, font: { size: 9, weight: '900' } } } },
                        onClick: (e, activeEls) => {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = Object.keys(categoryData)[index];
                                window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { category_name: label });
                            }
                        }
                    }
                });

                // Trend Chart
                const trendData = {!! json_encode($ticketsPerDay) !!};
                new Chart(document.getElementById('trendChart'), {
                    type: 'line',
                    data: {
                        labels: Object.keys(trendData).map(d => d.split('-').slice(1).reverse().join('/')),
                        datasets: [{
                            label: 'NEW INGRESS',
                            data: Object.values(trendData),
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        scales: { 
                            y: { beginAtZero: true, ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        },
                        plugins: { legend: { display: false } },
                        onClick: (e, activeEls) => {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = Object.keys(trendData)[index];
                                // Trend logic: if YYYY-MM-DD, it's specific. If YYYY-MM, it's monthly ingress.
                                const paramKey = label.length === 7 ? 'month' : 'date';
                                window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { [paramKey]: label });
                            }
                        }
                    }
                });

                // Mining Cluster Chart (Command Center)
                @if($miningData && isset($miningData['departments']))
                new Chart(document.getElementById('miningClusterChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(collect($miningData['departments'])->sortByDesc('report_count')->pluck('department')) !!},
                        datasets: [{
                            label: 'Report Volume',
                            data: {!! json_encode(collect($miningData['departments'])->sortByDesc('report_count')->pluck('report_count')) !!},
                            backgroundColor: {!! json_encode(collect($miningData['departments'])->sortByDesc('report_count')->map(function($d) {
                                return $d['intensity'] == 'High' ? '#f43f5e' : ($d['intensity'] == 'Medium' ? '#f59e0b' : '#10b981');
                            })->values()) !!},
                            borderRadius: 6,
                            barThickness: 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                titleColor: '#111827',
                                bodyColor: '#4b5563',
                                borderColor: 'rgba(229, 231, 235, 1)',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 8,
                                titleFont: { size: 12, weight: 'bold' },
                                bodyFont: { size: 11 },
                                displayColors: false,
                                callbacks: {
                                    afterLabel: function(context) {
                                        const deptData = {!! json_encode(collect($miningData['departments'])->keyBy('department')) !!};
                                        const dept = deptData[context.label];
                                        return [
                                            'Status: ' + dept.intensity,
                                            'Primary Domain: ' + dept.top_category
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' },
                                ticks: { color: '#9ca3af', font: { size: 9, weight: 'bold' } }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: '#111827', font: { size: 10, weight: '900' } }
                            }
                        },
                        onClick: (e, activeEls) => {
                            if (activeEls.length > 0) {
                                const index = activeEls[0].index;
                                const label = {!! json_encode(collect($miningData['departments'])->sortByDesc('report_count')->pluck('department')) !!}[index];
                                window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { department: label });
                            }
                        }
                    }
                });
                @endif
            </script>
        </div>
    </div>
</x-app-layout>
