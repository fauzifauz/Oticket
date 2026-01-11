<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
            <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                {{ __('Analytics & Reports Dashboard') }}
            </h2>
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full lg:w-auto mt-4 lg:mt-0">
                <!-- General Exports -->
                <div class="flex items-center h-11 sm:h-10 lg:h-9 bg-white border border-gray-300 rounded-xl lg:rounded-lg shadow-sm overflow-hidden shrink-0">
                    <a href="{{ route('admin.tickets.export-pdf') }}" class="flex-1 lg:flex-none h-full inline-flex items-center justify-center px-5 lg:px-3 border-r border-gray-200 font-bold text-[10px] sm:text-[11px] lg:text-[9px] text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition" title="Export General PDF">
                        <svg class="w-4 h-4 lg:w-3.5 lg:h-3.5 mr-2 lg:mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </a>
                    <a href="{{ route('admin.tickets.export') }}" class="flex-1 lg:flex-none h-full inline-flex items-center justify-center px-5 lg:px-3 font-bold text-[10px] sm:text-[11px] lg:text-[9px] text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition" title="Export General CSV">
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

    <div class="py-6 bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Analytical Context Filter -->
            <div class="bg-white p-3 sm:p-4 rounded-xl sm:rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="p-1.5 sm:p-2 bg-blue-50 rounded-lg">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-bold text-gray-900 leading-tight">Data Scope</h3>
                            <p class="text-[8px] sm:text-[10px] font-medium text-gray-500 uppercase tracking-widest">Filter analytics by period</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.analytics.index') }}" method="GET" class="w-full lg:w-auto">
                        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-3">
                            <select name="year" onchange="this.form.submit()" class="h-8 sm:h-9 px-2 sm:px-3 py-0 bg-gray-50 border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold text-gray-700 focus:ring-blue-500/20 focus:border-blue-500">
                                <option value="">All Years</option>
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>

                            <input type="month" name="month" value="{{ request('month') }}" onchange="this.form.submit()"
                                   class="h-8 sm:h-9 px-2 sm:px-3 py-0 bg-gray-50 border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold text-gray-700 focus:ring-blue-500/20 focus:border-blue-500">

                            <input type="week" name="week" value="{{ request('week') }}" onchange="this.form.submit()"
                                   class="h-8 sm:h-9 px-2 sm:px-3 py-0 bg-gray-50 border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold text-gray-700 focus:ring-blue-500/20 focus:border-blue-500 col-span-1">

                            <a href="{{ route('admin.analytics.index') }}" class="h-8 sm:h-9 px-3 sm:px-4 inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-[10px] sm:text-xs font-bold transition-all truncate">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Summary Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-4 lg:mb-6">
                <!-- SLA Compliance -->
                <div class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute top-0 right-0 p-2 lg:p-3 opacity-10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[7px] lg:text-[9px] font-bold text-gray-400 uppercase tracking-wider">SLA Compliance</p>
                    <div class="flex items-baseline gap-1 lg:gap-2 mt-1">
                        <p class="text-xl lg:text-3xl font-extrabold text-blue-600">{{ $slaCompliance }}%</p>
                    </div>
                    <div class="mt-1 lg:mt-2">
                        <div class="w-full bg-gray-100 rounded-full h-1 overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ $slaCompliance }}%"></div>
                        </div>
                        <p class="text-[7px] lg:text-[9px] text-gray-500 mt-1 font-medium leading-tight">{{ $resolvedWithinSla }} / {{ $totalWithSla }} metrics on track</p>
                    </div>
                </div>

                <!-- Avg Response Time -->
                <div class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute top-0 right-0 p-2 lg:p-3 opacity-10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[7px] lg:text-[9px] font-bold text-gray-400 uppercase tracking-wider">Avg Response Time</p>
                    <div class="flex items-baseline gap-1 lg:gap-2 mt-1">
                        <p class="text-xl lg:text-3xl font-extrabold text-indigo-600">{{ $avgResponseTime }}</p>
                        <span class="text-[8px] lg:text-[10px] font-semibold text-gray-500 uppercase">min</span>
                    </div>
                    <p class="text-[7px] lg:text-[9px] text-gray-500 mt-1 lg:mt-2 font-medium italic leading-tight">Average time to first reply</p>
                </div>

                <!-- Average Rating -->
                <a href="{{ route('admin.tickets.index', request()->only(['year', 'month', 'week'])) }}" class="bg-white p-3 lg:p-4 rounded-xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all duration-300 block">
                    <div class="absolute top-0 right-0 p-2 lg:p-3 opacity-10 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <p class="text-[7px] lg:text-[9px] font-bold text-gray-400 uppercase tracking-wider">Average Rating</p>
                    <div class="flex items-baseline gap-1 lg:gap-2 mt-1">
                        <p class="text-xl lg:text-3xl font-extrabold text-yellow-500">{{ number_format($avgRating, 1) }}</p>
                        <span class="text-[8px] lg:text-[10px] font-semibold text-gray-400 uppercase">/ 5.0</span>
                    </div>
                    <div class="flex items-center mt-1 lg:mt-2 gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-3 h-3 lg:w-4 lg:h-4 {{ $i <= round($avgRating) ? 'text-yellow-400 fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                </a>

                <!-- Resolved Tickets -->
                <a href="{{ route('admin.tickets.index', array_merge(['is_resolved' => 1], request()->only(['year', 'month', 'week']))) }}" class="bg-gradient-to-br from-indigo-600 to-purple-700 p-3 lg:p-4 rounded-xl shadow-lg shadow-indigo-100 relative overflow-hidden group block hover:shadow-2xl transition-all">
                    <div class="absolute top-0 right-0 p-2 lg:p-3 opacity-20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 lg:w-12 lg:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-[7px] lg:text-[9px] font-bold text-indigo-100 uppercase tracking-wider">Total Resolved</p>
                    <div class="flex items-baseline gap-1 lg:gap-2 mt-1">
                        <p class="text-xl lg:text-3xl font-extrabold text-white">{{ $totalResolved }}</p>
                    </div>
                    <p class="text-[7px] lg:text-[9px] text-indigo-100 mt-1 lg:mt-2 font-medium leading-tight">System performance is healthy</p>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
                <!-- Support Performance -->
                <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-extrabold text-gray-800 tracking-tight">Support Staff</h3>
                        <span class="px-2 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded uppercase tracking-wider">Top Performers</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($supportPerformance as $staff)
                        <a href="{{ route('admin.tickets.index', array_merge(['assigned_to' => $staff->name], request()->only(['year', 'month', 'week']))) }}" class="flex items-center justify-between p-4 bg-gray-50/50 rounded-xl border border-transparent hover:border-blue-100 hover:bg-white hover:shadow-sm transition-all duration-200 group overflow-hidden gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold uppercase group-hover:bg-blue-600 group-hover:text-white transition-colors shrink-0">
                                    {{ substr($staff->name, 0, 2) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $staff->name }}</p>
                                    <p class="text-[10px] font-medium text-gray-400 font-mono tracking-tighter truncate">{{ $staff->resolved_count }} RESOLVED TICKETS</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-extrabold text-yellow-600 flex items-center gap-1 group-hover:scale-110 transition-transform">
                                    {{ number_format($staff->avg_rating, 1) }}
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Monthly Trend -->
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-extrabold text-gray-800 tracking-tight">Monthly Ticket Trend</h3>
                        <div class="flex gap-2">
                             <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                VOLUME
                             </span>
                        </div>
                    </div>
                    <div class="flex-grow min-h-[300px]">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <h3 class="font-extrabold text-gray-800 tracking-tight mb-6">Tickets by Category</h3>
                    <div class="flex-grow min-h-[300px]">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative group">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-extrabold text-gray-800 tracking-tight">Top IT Issues</h3>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Frequency by Subject</p>
                        </div>
                    </div>
                    <div class="flex-grow min-h-[300px]">
                        <canvas id="topProblemsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Custom Plugin for modern look
        const shadowPlugin = {
            id: 'shadowPlugin',
            beforeDraw: (chart) => {
                const { ctx } = chart;
                ctx.save();
                ctx.shadowColor = 'rgba(0, 0, 0, 0.05)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 5;
            },
            afterDraw: (chart) => {
                chart.ctx.restore();
            }
        };

        // Common Options with modern styling
        const modernOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 11, weight: '600' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1f2937',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    displayColors: false
                }
            },
            onHover: (event, chartElement) => {
                event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
            }
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

        // Monthly Trend Chart
        new Chart(document.getElementById('monthlyTrendChart'), {
            type: 'line',
            plugins: [shadowPlugin],
            data: {
                labels: {!! json_encode($monthlyTrend->keys()) !!},
                datasets: [{
                    label: 'Total Tickets',
                    data: {!! json_encode($monthlyTrend->values()) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                ...modernOptions,
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#9ca3af' }
                    },
                    y: {
                        border: { display: false },
                        grid: { color: '#f3f4f6', dash: [4, 4] },
                        ticks: { font: { size: 10, weight: '600' }, color: '#9ca3af', padding: 10 }
                    }
                },
                onClick: (e, elements, chart) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const label = chart.data.labels[index];
                        // If label is YYYY-MM, use month param. If YYYY-MM-DD, use date param.
                        const paramKey = label.length === 7 ? 'month' : 'date';
                        window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { [paramKey]: label });
                    }
                }
            }
        });

        // Category Chart
        new Chart(document.getElementById('categoryPieChart'), {
            type: 'doughnut',
            plugins: [shadowPlugin],
            data: {
                labels: {!! json_encode($ticketsByCategory->keys()) !!},
                datasets: [{
                    label: 'Tickets',
                    data: {!! json_encode($ticketsByCategory->values()) !!},
                    backgroundColor: ['#6366f1', '#f43f5e', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                ...modernOptions,
                plugins: { 
                    ...modernOptions.plugins, 
                    legend: { 
                        display: true,
                        position: 'right' 
                    } 
                },
                // Scales removed because Doughnut chart doesn't use axes
                onClick: (e, elements, chart) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const label = chart.data.labels[index];
                        window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { category_name: label });
                    }
                }
            }
        });

        // Top Problems Chart
        new Chart(document.getElementById('topProblemsChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($topProblemsLabels) !!},
                datasets: [{
                    label: 'Total Tickets',
                    data: {!! json_encode($topProblemsValues) !!},
                    backgroundColor: '#f59e0b',
                    borderRadius: 8,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1f2937' }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: '600' }, color: '#9ca3af' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 10, weight: '600' }, color: '#9ca3af' },
                        grid: { color: '#f3f4f6', dash: [4, 4] },
                        border: { display: false }
                    }
                },
                onHover: (event, chartElement) => {
                    event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                },
                onClick: (e, elements, chart) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const label = chart.data.labels[index];
                        window.location.href = getFilteredUrl(`{{ route('admin.tickets.index') }}`, { subject: label });
                    }
                }
            }
        });

    </script>
    @endpush
</x-app-layout>

