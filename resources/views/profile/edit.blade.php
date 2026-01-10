<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-lg sm:text-2xl text-gray-900 tracking-tight leading-tight">
                    {{ __('User Credentials') }}
                </h2>
                <p class="text-[9px] sm:text-xs font-medium text-gray-500 mt-0.5 uppercase tracking-widest leading-none">Manage your administrative identity</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[9px] font-black uppercase tracking-widest">
                    Verified
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Card 1: Account Identification (8 Columns) -->
                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 sm:px-8 py-4 sm:py-6 border-b border-gray-100 bg-white text-left">
                            <h3 class="text-[10px] sm:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1 h-1 sm:w-2 sm:h-2 rounded-full bg-indigo-500"></span>
                                Identity Profile
                            </h3>
                        </div>
                        <div class="p-4 sm:p-8">
                            <div class="grid grid-cols-2 sm:grid-cols-2 gap-x-4 gap-y-6 sm:gap-8 text-left mb-8 sm:mb-10">
                                <div class="space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Legal Name</p>
                                    <p class="text-[13px] sm:text-sm font-black text-gray-900 uppercase truncate">{{ $user->name }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Digital Address</p>
                                    <p class="text-[13px] sm:text-sm font-black text-gray-900 truncate">{{ $user->email }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Protocol Role</p>
                                    <span class="inline-flex px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg text-[9px] sm:text-[10px] font-black uppercase tracking-widest">{{ $user->role }}</span>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Functional Department</p>
                                    <p class="text-[13px] sm:text-sm font-black text-gray-900 uppercase truncate">{{ $user->department ?? 'OPERATIONAL' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Contact Protocol</p>
                                    <p class="text-[13px] sm:text-sm font-black text-gray-900">{{ $user->phone ?? 'PENDING' }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Status</p>
                                    <p class="text-[13px] sm:text-sm font-black text-gray-900 uppercase">{{ $user->status ? 'Active' : 'Pending' }}</p>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-gray-50">
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6 px-1">Update Protocol Data</h4>
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Security & Destruction (4 Columns) -->
                <div class="lg:col-span-4 space-y-8">
                    <!-- Security section -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-5 sm:px-8 py-4 sm:py-6 border-b border-gray-100 bg-white text-left">
                            <h3 class="text-[10px] sm:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1 h-1 sm:w-2 sm:h-2 rounded-full bg-amber-500"></span>
                                Security Protocol
                            </h3>
                        </div>
                        <div class="p-4 sm:p-8 text-left">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <div class="bg-rose-50 rounded-2xl sm:rounded-3xl border border-rose-100 overflow-hidden">
                        <div class="px-5 sm:px-8 py-4 sm:py-6 border-b border-rose-100 bg-rose-50 text-left">
                            <h3 class="text-[10px] sm:text-xs font-black text-rose-700 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1 h-1 sm:w-2 sm:h-2 rounded-full bg-rose-500 animate-pulse"></span>
                                Terminal Action
                            </h3>
                        </div>
                        <div class="p-4 sm:p-8 text-left">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
