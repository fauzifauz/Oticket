<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-gray-900/50 backdrop-blur-sm lg:hidden"></div>
 
 <div x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-72 overflow-y-auto transition duration-500 transform bg-white lg:translate-x-0 lg:static lg:inset-0 border-r border-gray-100 flex flex-col justify-between shadow-xl shadow-gray-100/50">
     
     <!-- Brand / Logo Area -->
     <div>
         <div class="flex items-center justify-center h-24 border-b border-dashed border-gray-200">
             <x-application-logo class="w-10 h-10 text-indigo-600 fill-current drop-shadow-sm" />
         </div>
 
         <nav class="mt-8 px-4 space-y-2">
             @if(Auth::user()->role === 'admin')
                 
                 <!-- Core Module -->
                 <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-6 mb-3">Core Module</p>
                 
                 <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/10">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                     <span>Command Center</span>
                 </x-nav-link>
 
                 <x-nav-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/10">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.analytics.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                     <span>Analytics & Reports</span>
                 </x-nav-link>
 
                 <!-- Entity Management -->
                 <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-8 mb-3">Entities</p>
 
                 <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/10">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                     <span>User Registry</span>
                 </x-nav-link>
 
                 <x-nav-link :href="route('admin.tickets.index')" :active="request()->routeIs('admin.tickets.*')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-indigo-50 text-indigo-600 ring-1 ring-inset ring-indigo-500/10">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.tickets.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                     <span>Ticket Operations</span>
                 </x-nav-link>
 
                 <!-- Classification & Config -->
                 <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-8 mb-3">Configuration</p>
 
                 <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')" 
                     class="group flex items-center px-4 py-3 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="text-indigo-600 bg-indigo-50 ring-1 ring-indigo-500/10" :inactive-class="'text-gray-500'">
                     <svg class="w-4 h-4 mr-3 transition-colors" :class="request()->routeIs('admin.categories.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                     <span>Categories</span>
                 </x-nav-link>
 
                 <x-nav-link :href="route('admin.statuses.index')" :active="request()->routeIs('admin.statuses.*')" 
                     class="group flex items-center px-4 py-3 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="text-indigo-600 bg-indigo-50 ring-1 ring-indigo-500/10" :inactive-class="'text-gray-500'">
                      <svg class="w-4 h-4 mr-3 transition-colors" :class="request()->routeIs('admin.statuses.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     <span>Workflow Status</span>
                 </x-nav-link>
 
                 <x-nav-link :href="route('admin.slas.index')" :active="request()->routeIs('admin.slas.*')" 
                     class="group flex items-center px-4 py-3 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="text-indigo-600 bg-indigo-50 ring-1 ring-indigo-500/10" :inactive-class="'text-gray-500'">
                     <svg class="w-4 h-4 mr-3 transition-colors" :class="request()->routeIs('admin.slas.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     <span>SLA Protocols</span>
                 </x-nav-link>
 
                 <!-- System Internals -->
                 <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-8 mb-3">System</p>
 
                 <x-nav-link :href="route('admin.activity_logs.index')" :active="request()->routeIs('admin.activity_logs.*')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-white text-indigo-600 shadow-md shadow-gray-200 ring-1 ring-gray-100">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.activity_logs.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                     <span>Audit Logs</span>
                 </x-nav-link>
 
                 <x-nav-link :href="route('admin.cms.index')" :active="request()->routeIs('admin.cms.*')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-white text-indigo-600 shadow-md shadow-gray-200 ring-1 ring-gray-100">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.cms.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                     <span>Content Management</span>
                 </x-nav-link>
 
                 <x-nav-link :href="route('admin.media.index')" :active="request()->routeIs('admin.media.*')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-white text-indigo-600 shadow-md shadow-gray-200 ring-1 ring-gray-100">
                     <svg class="w-5 h-5 mr-3 transition-colors" :class="request()->routeIs('admin.media.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                     <span>Media Blobs</span>
                 </x-nav-link>
                 
             @elseif(Auth::user()->role === 'support')
                 <!-- Support Sidebar -->
                 <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-6 mb-3">Support Console</p>
                 <x-nav-link :href="route('support.dashboard')" :active="request()->routeIs('support.dashboard')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-indigo-50 text-indigo-600 shadow-sm">
                     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                     <span>Dashboard</span>
                 </x-nav-link>
             @else
                  <!-- User Sidebar -->
                 <p class="px-4 text-[10px] font-black text-gray-400 uppercase tracking-widest mt-6 mb-3">My Portal</p>
                 <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                     class="group flex items-center px-4 py-3.5 text-gray-500 font-bold text-xs rounded-xl transition-all duration-200 hover:bg-gray-50 hover:text-gray-900" 
                     class-active="bg-indigo-50 text-indigo-600 shadow-sm">
                     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                     <span>My Dashboard</span>
                 </x-nav-link>
             @endif
         </nav>
     </div>
 
     <!-- Sidebar Footer -->
     <div class="p-4 border-t border-gray-100 bg-gray-50/50">
         <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
             <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-xs">
                 {{ substr(Auth::user()->name, 0, 2) }}
             </div>
             <div class="overflow-hidden">
                 <p class="text-[10px] font-black text-gray-900 uppercase tracking-wider truncate">{{ Auth::user()->name }}</p>
                 <p class="text-[9px] text-gray-400 font-bold truncate">{{ Auth::user()->role }}</p>
             </div>
         </div>
     </div>
 </div>
