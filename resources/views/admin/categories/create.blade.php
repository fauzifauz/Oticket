<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 text-left">
            <a href="{{ route('admin.categories.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Create Category Asset') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Define new incident classification and its priority layer</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-white text-left">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Category Configuration
                    </h3>
                </div>

                <div class="p-8 text-left">
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-8">
                        @csrf
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Category Registry Name</label>
                            <input type="text" name="name" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight placeholder:italic" 
                                placeholder="E.G. NETWORK INFRASTRUCTURE"
                                required>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Default Priority Protocol (SLA ALIGNED)</label>
                            <div class="relative">
                                <select name="priority" 
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight appearance-none cursor-pointer" 
                                    required>
                                    <option value="" disabled selected>Select Operational Priority</option>
                                    @foreach($slaRules as $sla)
                                        <option value="{{ $sla->priority }}">{{ $sla->priority }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 font-medium px-1 italic">This priority will be automatically applied to new tickets under this category.</p>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Initialize Category Asset
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="block w-full text-center mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                Cancel Operation
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
