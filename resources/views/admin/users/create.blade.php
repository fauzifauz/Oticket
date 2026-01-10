<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Add New User') }}
                </h2>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Register a new organization member</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 md:p-12 text-left">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-8 uppercase tracking-tight">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Name -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1" for="name">Full Name</label>
                                <input class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200" 
                                       id="name" type="text" name="name" placeholder="John Doe" required>
                                @error('name') <p class="text-xs font-bold text-rose-500 mt-1 ml-1 lowercase tracking-normal">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1" for="email">Email Address</label>
                                <input class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200" 
                                       id="email" type="email" name="email" placeholder="john@example.com" required>
                                @error('email') <p class="text-xs font-bold text-rose-500 mt-1 ml-1 lowercase tracking-normal">{{ $message }}</p> @enderror
                            </div>

                            <!-- Department -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1">Department</label>
                                <input type="text" name="department" placeholder="IT Support"
                                       class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200">
                            </div>

                            <!-- Phone -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1">Phone Number</label>
                                <input type="text" name="phone" placeholder="+62 895..."
                                       class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200">
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1" for="password">Security Password</label>
                                <input class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200" 
                                       id="password" type="password" name="password" required>
                                @error('password') <p class="text-xs font-bold text-rose-500 mt-1 ml-1 lowercase tracking-normal">{{ $message }}</p> @enderror
                            </div>

                            <!-- Role -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1" for="role">Assign Role</label>
                                <div class="relative">
                                    <select class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 appearance-none" 
                                            id="role" name="role">
                                        <option value="user">EMPLOYEE (USER)</option>
                                        <option value="support">IT SUPPORT</option>
                                        <option value="admin">ADMINISTRATOR</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                             <!-- Status -->
                             <div class="space-y-2">
                                <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest ml-1" for="status">Initial Status</label>
                                <div class="relative">
                                    <select class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-3.5 text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all duration-200 appearance-none" 
                                            id="status" name="status">
                                        <option value="1">ACTIVE / APPROVED</option>
                                        <option value="0">INACTIVE / PENDING</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-50 flex flex-col md:flex-row items-center justify-between gap-4">
                            <a href="{{ route('admin.users.index') }}" class="text-[10px] font-extrabold text-gray-400 hover:text-gray-600 transition tracking-widest uppercase">
                                Cancel Registration
                            </a>
                            <button class="w-full md:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-100 transition-all duration-200 active:scale-95" type="submit">
                                Create User Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
