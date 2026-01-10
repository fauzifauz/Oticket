<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('User Management') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Manage your organization members</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <a href="{{ route('admin.users.export-pdf') }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-[10px] sm:text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition duration-150">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.users.export') }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-[10px] sm:text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition duration-150">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-3 sm:px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-[10px] sm:text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition duration-150">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8 text-left">
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4 font-black">
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-indigo-50 rounded-lg sm:rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Total</p>
                        <p class="text-base sm:text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['total'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-blue-50 rounded-lg sm:rounded-xl flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Users</p>
                        <p class="text-base sm:text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['employees'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-purple-50 rounded-lg sm:rounded-xl flex items-center justify-center text-purple-600">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Staff</p>
                        <p class="text-base sm:text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['staff'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 sm:w-12 sm:h-12 bg-orange-50 rounded-lg sm:rounded-xl flex items-center justify-center text-orange-600">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[8px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Inactive</p>
                        <p class="text-base sm:text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Navigation & Filtering -->
                <div class="px-5 sm:px-8 pt-5 sm:pt-6 border-b border-gray-100 bg-white">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <nav class="flex space-x-6 text-left overflow-x-auto whitespace-nowrap scrollbar-hide border-b border-gray-50 md:border-0">
                            <a href="{{ route('admin.users.index', ['type' => 'employee', 'search' => request('search')]) }}" 
                               class="pb-4 px-1 border-b-2 font-bold text-[10px] sm:text-xs uppercase tracking-widest transition duration-150 {{ $type === 'employee' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-200' }}">
                                Users
                            </a>
                            <a href="{{ route('admin.users.index', ['type' => 'staff', 'search' => request('search')]) }}" 
                               class="pb-4 px-1 border-b-2 font-bold text-[10px] sm:text-xs uppercase tracking-widest transition duration-150 {{ $type === 'staff' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-200' }}">
                                Staff
                            </a>
                        </nav>
                        
                        <div class="flex items-center gap-4 pb-4 md:pb-0">
                            <form action="{{ route('admin.users.index') }}" method="GET" class="relative group flex-1 md:flex-none">
                                <input type="hidden" name="type" value="{{ $type }}">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                                       class="pl-10 pr-4 py-2 bg-gray-50 border-gray-100 rounded-xl text-[10px] sm:text-xs font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 w-full md:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </form>
                            <h3 class="font-extrabold text-gray-800 tracking-tight whitespace-nowrap hidden lg:block">
                                {{ $type === 'staff' ? 'Staff Members' : 'Employee Repository' }}
                                <span class="ml-1 px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] rounded-full">{{ $users->total() }}</span>
                            </h3>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mx-8 mt-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-700 text-sm font-bold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Desktop/Tablet Table View -->
                <div class="hidden md:block overflow-x-auto text-left">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">ID</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Name</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Email</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Role</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Tickets</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Department</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Phone</th>
                                <th class="px-4 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Created</th>
                                <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 uppercase tracking-tight">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50/70 transition-all duration-200 group text-[11px] font-bold text-gray-700">
                                <td class="px-4 py-5 text-center font-mono text-gray-400">{{ $user->id }}</td>
                                <td class="px-4 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-[10px] font-black shadow-sm uppercase">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <p class="font-black text-gray-900 group-hover:text-indigo-600 transition-colors truncate max-w-[120px]">{{ $user->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-5 lowercase tracking-normal font-medium text-gray-500">{{ $user->email }}</td>
                                <td class="px-4 py-5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-rose-50 text-rose-600' : ($user->role === 'support' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <a href="{{ route('admin.users.tickets', $user->id) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-700 text-[10px] font-extrabold hover:bg-indigo-600 hover:text-white transition duration-150">
                                        {{ $user->tickets_count }}
                                    </a>
                                </td>
                                <td class="px-4 py-5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300' }}"></span>
                                        <span class="text-[9px] font-extrabold uppercase tracking-widest {{ $user->status ? 'text-emerald-600' : 'text-gray-400' }}">
                                            {{ $user->status ? 'Approved' : 'Pending' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-5 font-medium text-gray-500 truncate max-w-[100px]">{{ $user->department ?? '-' }}</td>
                                <td class="px-4 py-5 font-medium text-gray-500">{{ $user->phone ?? '-' }}</td>
                                <td class="px-4 py-5 font-semibold text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-5 text-right whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-1">
                                         @if($user->status)
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="inline-flex items-center px-2 py-1 text-[9px] font-black uppercase tracking-widest text-orange-600 hover:bg-orange-50 rounded-lg transition" title="Deactivate Account">
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="inline-flex items-center px-2 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Approve Account">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center px-2 py-1 text-[9px] font-black uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit Profile">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Silakan konfirmasi untuk menghapus user ini. Langkah ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 text-[9px] font-black uppercase tracking-widest text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete Account">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4 p-4">
                    @foreach($users as $user)
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white text-xs font-black shadow-sm uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-900 text-sm">{{ $user->name }}</p>
                                        <p class="text-[10px] font-medium text-gray-400 lowercase tracking-normal">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-rose-50 text-rose-600' : ($user->role === 'support' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600') }}">
                                    {{ $user->role }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2 text-[10px] font-bold text-gray-500 border-t border-b border-gray-50 py-3">
                                <div>
                                    <p class="text-gray-300 uppercase tracking-widest mb-0.5">Department</p>
                                    <p class="text-gray-700">{{ $user->department ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-300 uppercase tracking-widest mb-0.5">Tickets</p>
                                    <a href="{{ route('admin.users.tickets', $user->id) }}" class="text-indigo-600 font-black hover:underline">{{ $user->tickets_count }}</a>
                                </div>
                                <div>
                                    <p class="text-gray-300 uppercase tracking-widest mb-0.5">Phone</p>
                                    <p class="text-gray-700">{{ $user->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-300 uppercase tracking-widest mb-0.5">Status</p>
                                    <span class="{{ $user->status ? 'text-emerald-600' : 'text-gray-400' }}">
                                        {{ $user->status ? 'Approved' : 'Pending' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-1">
                                <span class="text-[9px] font-bold text-gray-300">{{ $user->created_at->format('d M Y') }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Edit</a>
                                     <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-400 hover:text-rose-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100">
                    {{ $users->appends(['type' => $type, 'search' => request('search')])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
