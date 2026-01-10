<nav class="bg-white border-b border-gray-100 shadow-sm z-10">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center min-w-0">
                <!-- Hamburger (Mobile/Tablet only) -->
                <button @click="sidebarOpen = !sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out lg:hidden shrink-0">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                 <!-- Logo (Mobile Only because Sidebar has it on Desktop) -->
                <div class="shrink-0 flex items-center lg:hidden ml-2">
                     <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
                    </a>
                </div>
            </div>

            <!-- Notification Bell & Settings Dropdown -->
            <div class="flex items-center ms-auto sm:ms-6 space-x-2 sm:space-x-3 shrink-0" 
                 x-data="{ 
                    notifications: [], 
                    stats: {}, 
                    count: 0,
                    loading: true,
                    fetchNotifications() {
                        fetch('{{ route('notifications.active') }}')
                            .then(response => response.json())
                            .then(data => {
                                this.notifications = data.notifications;
                                this.stats = data.stats;
                                this.count = data.count;
                                this.loading = false;
                            })
                            .catch(error => console.error('Error fetching notifications:', error));
                    }
                 }" 
                 x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)">
                
                <!-- Notification Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <button @click="open = ! open" class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-indigo-600 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <template x-if="count > 0">
                            <span class="absolute top-1 right-2 inline-flex items-center justify-center w-4 h-4 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-rose-600 rounded-full" x-text="count"></span>
                        </template>
                    </button>

                    <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-72 max-w-[calc(100vw-2rem)] right-[-3rem] sm:right-0 rounded-md shadow-lg origin-top-right bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                            style="display: none;"
                            @click="open = false">
                        <div class="rounded-md ring-1 ring-black ring-opacity-5 content">
                             <div class="w-full">
                                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Notifications</p>
                                        <span class="text-[9px] font-bold text-gray-400 uppercase" x-text="count + ' recent'"></span>
                                    </div>
                                    
                                    <!-- Role Specific Stats -->
                                    <div class="mt-2 grid grid-cols-2 gap-2">
                                        <template x-if="stats.total !== undefined">
                                            <div class="bg-indigo-50 px-2 py-1 rounded-md">
                                                <p class="text-[9px] font-bold text-indigo-600 uppercase">Total Tickets</p>
                                                <p class="text-sm font-black text-indigo-900" x-text="stats.total"></p>
                                            </div>
                                        </template>
                                        <template x-if="stats.unassigned !== undefined">
                                            <div class="bg-rose-50 px-2 py-1 rounded-md">
                                                <p class="text-[9px] font-bold text-rose-600 uppercase">Unassigned</p>
                                                <p class="text-sm font-black text-rose-900" x-text="stats.unassigned"></p>
                                            </div>
                                        </template>
                                        <template x-if="stats.pending_users > 0">
                                            <div class="bg-amber-50 px-2 py-1 rounded-md">
                                                <p class="text-[9px] font-bold text-amber-600 uppercase">Pending Users</p>
                                                <p class="text-sm font-black text-amber-900" x-text="stats.pending_users"></p>
                                            </div>
                                        </template>
                                        <template x-if="stats.handling !== undefined">
                                            <div class="bg-amber-50 px-2 py-1 rounded-md col-span-2">
                                                <p class="text-[9px] font-bold text-amber-600 uppercase">Tickets Handling</p>
                                                <p class="text-sm font-black text-amber-900" x-text="stats.handling"></p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <div class="max-h-64 overflow-y-auto">
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div class="relative group border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors block">
                                            <a :href="notification.route" class="block px-4 py-3">
                                                <div class="flex items-start gap-3">
                                                    <div :class="'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-' + (notification.color || 'gray') + '-50 text-' + (notification.color || 'gray') + '-600'">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="notification.icon"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0 pr-4">
                                                        <div class="flex justify-between items-start">
                                                            <p class="text-xs font-black text-gray-900 uppercase tracking-tight" x-text="notification.title"></p>
                                                            <template x-if="notification.is_new">
                                                                <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                                                            </template>
                                                        </div>
                                                        <p class="text-[11px] text-gray-600 font-bold mt-0.5 truncate" x-text="notification.message"></p>
                                                        <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase" x-text="notification.created_at"></p>
                                                    </div>

                                                </div>
                                            </a>
                                        </div>
                                    </template>

                                    <template x-if="notifications.length === 0">
                                        <div class="px-4 py-8 text-center">
                                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">All Clear!</p>
                                            <p class="text-[10px] text-gray-400 font-bold mt-1 text-center">No recent notifications</p>
                                        </div>
                                    </template>
                                </div>

                                <!-- Dropdown Footer / History Link -->
                                <div class="px-4 py-2 border-t border-gray-100 bg-gray-50 text-center">
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.tickets.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-indigo-600 uppercase tracking-widest transition-colors">View Ticket History</a>
                                    @elseif(Auth::user()->role === 'support')
                                        <a href="{{ route('support.tickets.index') }}" class="text-[10px] font-bold text-gray-500 hover:text-indigo-600 uppercase tracking-widest transition-colors">View My Ticket History</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="text-[10px] font-bold text-gray-500 hover:text-indigo-600 uppercase tracking-widest transition-colors">View My History</a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-8 w-px bg-gray-200 mx-1 sm:mx-2"></div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-1 sm:px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-indigo-600 focus:outline-none transition ease-in-out duration-150">
                            <div class="font-bold hidden sm:block">{{ Auth::user()->name }}</div>
                            <div class="font-bold sm:hidden">{{ substr(Auth::user()->name, 0, 2) }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
