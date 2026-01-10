<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 text-left">
            <a href="{{ route('admin.statuses.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Initialize New Status') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Define a new operational stage in the incident lifecycle</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-white text-left">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Lifecycle State Definition
                    </h3>
                </div>

                <div class="p-8 text-left">
                    <form action="{{ route('admin.statuses.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="space-y-3 text-left">
                            <label for="name" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Protocol Status Label</label>
                            <input type="text" name="name" id="name" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight placeholder:italic @error('name') border-rose-500 @enderror" 
                                placeholder="E.G. IN PROGRESS" required>
                            @error('name') 
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="space-y-3 text-left">
                            <label for="color" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Identify Color (Tailwind Color Name)</label>
                            <select name="color" id="color" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('color') border-rose-500 @enderror" 
                                required>
                                <option value="gray">Neutral / Gray</option>
                                <option value="rose">Urgent / Rose</option>
                                <option value="indigo">Active / Indigo</option>
                                <option value="emerald">Resolved / Emerald</option>
                                <option value="amber">Warning / Amber</option>
                                <option value="blue">Info / Blue</option>
                                <option value="violet">Processing / Violet</option>
                            </select>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-1 italic">This color will be used for labels and indicators across the system.</p>
                            @error('color') 
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="pt-4 text-left">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Deploy Lifecycle Status
                            </button>
                            <a href="{{ route('admin.statuses.index') }}" class="block w-full text-center mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                Abort Protocol
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
