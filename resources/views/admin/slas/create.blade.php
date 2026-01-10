<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 text-left">
            <a href="{{ route('admin.slas.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Define SLA Standard') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Establish response and resolution targets for a priority class</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-white text-left">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Standardization Protocol
                    </h3>
                </div>

                <div class="p-8 text-left">
                    <form method="POST" action="{{ route('admin.slas.store') }}" class="space-y-8">
                        @csrf
                        
                        <div class="space-y-3">
                            <label for="priority" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Classification Identity (Priority)</label>
                            <input type="text" name="priority" id="priority" value="{{ old('priority') }}"
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight placeholder:italic @error('priority') border-rose-500 @enderror" 
                                placeholder="E.G. ULTRA URGENT" required>
                            @error('priority') 
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="space-y-3">
                            <label for="color" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Identity Color (Tailwind Color Name)</label>
                            <select name="color" id="color" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('color') border-rose-500 @enderror" 
                                required>
                                <option value="gray" {{ old('color') == 'gray' ? 'selected' : '' }}>Neutral / Gray</option>
                                <option value="rose" {{ old('color') == 'rose' ? 'selected' : '' }}>Urgent / Rose</option>
                                <option value="indigo" {{ old('color') == 'indigo' ? 'selected' : '' }}>Active / Indigo</option>
                                <option value="emerald" {{ old('color') == 'emerald' ? 'selected' : '' }}>Resolved / Emerald</option>
                                <option value="amber" {{ old('color') == 'amber' ? 'selected' : '' }}>Warning / Amber</option>
                                <option value="blue" {{ old('color') == 'blue' ? 'selected' : '' }}>Info / Blue</option>
                                <option value="violet" {{ old('color') == 'violet' ? 'selected' : '' }}>Processing / Violet</option>
                            </select>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-1 italic">This color will be used for labels and indicators across the system.</p>
                            @error('color') 
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label for="response_time_minutes" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Response Target (Minutes)</label>
                                <div class="relative">
                                    <input type="number" name="response_time_minutes" id="response_time_minutes" 
                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('response_time_minutes') border-rose-500 @enderror" 
                                        required>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center pointer-events-none">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">MINS</span>
                                    </div>
                                </div>
                                @error('response_time_minutes') 
                                    <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                                @enderror
                            </div>

                            <div class="space-y-3">
                                <label for="resolution_time_minutes" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Resolution Target (Minutes)</label>
                                <div class="relative">
                                    <input type="number" name="resolution_time_minutes" id="resolution_time_minutes" 
                                        class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('resolution_time_minutes') border-rose-500 @enderror" 
                                        required>
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center pointer-events-none">
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">MINS</span>
                                    </div>
                                </div>
                                @error('resolution_time_minutes') 
                                    <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Deploy SLA Standard
                            </button>
                            <a href="{{ route('admin.slas.index') }}" class="block w-full text-center mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                Abandon Configuration
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-8 bg-amber-50 rounded-2xl p-6 border border-amber-100 flex gap-4 text-left">
                <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h5 class="text-[10px] font-black text-amber-700 uppercase tracking-widest mb-1">Standardization Tip</h5>
                    <p class="text-[11px] font-bold text-amber-600/80 uppercase tracking-tight leading-relaxed">
                        Resolution time must logically be greater than or equal to response time. Ensure these values align with the PINS IT operational service capacity.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
