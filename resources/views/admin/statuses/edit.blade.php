<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4 text-left">
            <a href="{{ route('admin.statuses.index') }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Modify Operational Status') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Adjust the parameters of an existing lifecycle state</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 text-left">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-white text-left">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            State Re-Configuration
                        </h3>
                        <span class="text-[9px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg uppercase tracking-widest">
                            Editing STAT-{{ $status->id }}
                        </span>
                    </div>
                </div>

                <div class="p-8 text-left">
                    <form action="{{ route('admin.statuses.update', $status->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-3 text-left">
                            <label for="name" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Update Status Label</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $status->name) }}"
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('name') border-rose-500 @enderror" 
                                required>
                            @error('name') 
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="space-y-3 text-left">
                            <label for="color" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Identify Color (Tailwind Color Name)</label>
                            <select name="color" id="color" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('color') border-rose-500 @enderror" 
                                required>
                                <option value="gray" {{ old('color', $status->color) == 'gray' ? 'selected' : '' }}>Neutral / Gray</option>
                                <option value="rose" {{ old('color', $status->color) == 'rose' ? 'selected' : '' }}>Urgent / Rose</option>
                                <option value="indigo" {{ old('color', $status->color) == 'indigo' ? 'selected' : '' }}>Active / Indigo</option>
                                <option value="emerald" {{ old('color', $status->color) == 'emerald' ? 'selected' : '' }}>Resolved / Emerald</option>
                                <option value="amber" {{ old('color', $status->color) == 'amber' ? 'selected' : '' }}>Warning / Amber</option>
                                <option value="blue" {{ old('color', $status->color) == 'blue' ? 'selected' : '' }}>Info / Blue</option>
                                <option value="violet" {{ old('color', $status->color) == 'violet' ? 'selected' : '' }}>Processing / Violet</option>
                            </select>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-1 italic">This color will be used for labels and indicators across the system.</p>
                            @error('color') 
                                <p class="text-[9px] font-black text-rose-600 uppercase tracking-widest px-1 italic mt-1">{{ $message }}</p> 
                            @enderror
                        </div>

                        <div class="pt-4 text-left">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Commit State Changes
                            </button>
                            <a href="{{ route('admin.statuses.index') }}" class="block w-full text-center mt-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                                Abandon Changes
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
