<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight">
                    {{ __('Landing Page Settings') }}
                </h2>
                <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Global content management and brand configuration</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.media.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white text-gray-700 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Asset Registry
                </a>
                <button type="submit" form="cms-form" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all duration-200 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 animate-fade-in-down text-left">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <form id="cms-form" action="{{ route('admin.cms.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 text-left">
                    <!-- Left Side: Content & Sections (8 Columns) -->
                    <div class="lg:col-span-8 space-y-8">
                        
                        <!-- 1. Hero & Identity -->
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                             <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>
                            <div class="px-8 py-6 border-b border-gray-100 bg-white">
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Hero & Brand Identity
                                </h3>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Primary landing page impact</p>
                            </div>
                            <div class="p-8 space-y-8">
                                @foreach(['hero_title', 'hero_description', 'hero_button_text'] as $key)
                                    @php $content = $contents->where('key', $key)->first(); @endphp
                                    @if($content)
                                        <div class="space-y-3">
                                            <label for="{{ $content->key }}" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">{{ strtoupper($content->label) }}</label>
                                            @if($content->type === 'textarea')
                                                <textarea id="{{ $content->key }}" name="{{ $content->key }}" rows="3" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight leading-relaxed">{{ $content->value }}</textarea>
                                            @else
                                                <input type="text" id="{{ $content->key }}" name="{{ $content->key }}" value="{{ $content->value }}" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight">
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- 2. Features / Cara Kerja Section -->
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                             <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>
                            <div class="px-8 py-6 border-b border-gray-100 bg-white">
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Features Section
                                </h3>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Explaining the "How It Works" process</p>
                            </div>
                            <div class="p-8 space-y-10">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    @foreach(['features_section_title', 'features_section_subtitle'] as $key)
                                        @php $content = $contents->where('key', $key)->first(); @endphp
                                        @if($content)
                                            <div class="space-y-3">
                                                <label for="{{ $content->key }}" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">{{ strtoupper($content->label) }}</label>
                                                <input type="text" id="{{ $content->key }}" name="{{ $content->key }}" value="{{ $content->value }}" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all uppercase tracking-tight">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                
                                <div class="space-y-6 pt-6 border-t border-gray-50">
                                    @foreach([1, 2, 3] as $step)
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                                            <div class="md:col-span-2 space-y-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Icon {{ $step }}</label>
                                                @php $content = $contents->where('key', "step_{$step}_icon")->first(); @endphp
                                                <input type="text" name="{{ "step_{$step}_icon" }}" value="{{ $content?->value }}" class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-center text-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                                            </div>
                                            <div class="md:col-span-4 space-y-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Title {{ $step }}</label>
                                                @php $content = $contents->where('key', "step_{$step}_title")->first(); @endphp
                                                <input type="text" name="{{ "step_{$step}_title" }}" value="{{ $content?->value }}" class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-xs font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all uppercase">
                                            </div>
                                            <div class="md:col-span-6 space-y-3">
                                                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">Description {{ $step }}</label>
                                                @php $content = $contents->where('key', "step_{$step}_desc")->first(); @endphp
                                                <textarea name="{{ "step_{$step}_desc" }}" rows="2" class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-[11px] font-bold text-gray-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all uppercase leading-relaxed">{{ $content?->value }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- 3. System Broadcast -->
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>
                            <div class="px-8 py-6 border-b border-gray-100 bg-amber-50/30">
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                    Employee Broadcast System
                                </h3>
                                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter mt-1">Live announcements for internal operatives</p>
                            </div>
                            <div class="p-8 space-y-6">
                                @foreach(['announcement_title', 'announcement_message'] as $key)
                                    @php $content = $contents->where('key', $key)->first(); @endphp
                                    @if($content)
                                        <div class="space-y-3">
                                            <label for="{{ $content->key }}" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">{{ strtoupper($content->label) }}</label>
                                            @if($content->type === 'textarea')
                                                <textarea id="{{ $content->key }}" name="{{ $content->key }}" rows="3" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all uppercase tracking-tight leading-relaxed placeholder:text-gray-300">{{ $content->value }}</textarea>
                                            @else
                                                <input type="text" id="{{ $content->key }}" name="{{ $content->key }}" value="{{ $content->value }}" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all uppercase tracking-tight">
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- 4. Footer & Legal -->
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative text-left">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-gray-900"></div>
                            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    Footer & Global Registry
                                </h3>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Legal links and company credentials</p>
                            </div>
                            <div class="p-8 space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    @foreach(['footer_company_name', 'footer_text'] as $key)
                                        @php $content = $contents->where('key', $key)->first(); @endphp
                                        @if($content)
                                            <div class="space-y-3">
                                                <label for="{{ $content->key }}" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">{{ strtoupper($content->label) }}</label>
                                                <input type="text" id="{{ $content->key }}" name="{{ $content->key }}" value="{{ $content->value }}" class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-gray-900/10 focus:border-gray-900 transition-all uppercase tracking-tight">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    @foreach(['footer_privacy_link', 'footer_terms_link', 'footer_contact_link'] as $key)
                                        @php $content = $contents->where('key', $key)->first(); @endphp
                                        @if($content)
                                            <div class="space-y-3">
                                                <label for="{{ $content->key }}" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">{{ strtoupper($content->label) }}</label>
                                                <input type="text" id="{{ $content->key }}" name="{{ $content->key }}" value="{{ $content->value }}" class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-[10px] font-bold text-indigo-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Brand Assets (4 Columns) -->
                    <div class="lg:col-span-4 space-y-8">
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-8 py-6 border-b border-gray-100 bg-gray-900">
                                <h3 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Visual Brand Assets
                                </h3>
                                <p class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter mt-1">Core media used across all portals</p>
                            </div>

                            <div class="p-8 space-y-10">
                                @php
                                    $brandAssets = $contents->whereIn('key', ['login_bg_image', 'login_form_logo', 'hero_bg_image', 'navbar_logo'])->sortBy(function($c) {
                                        return array_search($c->key, ['login_bg_image', 'login_form_logo', 'hero_bg_image', 'navbar_logo']);
                                    });
                                @endphp

                                @foreach($brandAssets as $content)
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">
                                            {{ strtoupper($content->label) }}
                                        </label>
                                        
                                        <div class="relative group rounded-2xl overflow-hidden border-2 border-dashed border-gray-100 hover:border-indigo-300 transition-all bg-gray-50 min-h-[120px] flex items-center justify-center">
                                            <div id="preview-container-{{ $content->key }}" class="w-full h-full flex items-center justify-center">
                                                @if($content->value)
                                                    <img id="preview-{{ $content->key }}" src="{{ asset('storage/' . $content->value) }}?v={{ time() }}" alt="Preview" class="max-w-full max-h-[200px] object-contain shadow-sm">
                                                @else
                                                    <div class="flex flex-col items-center justify-center gap-2 text-gray-300 py-8">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        <span class="text-[8px] font-black uppercase tracking-widest text-center px-4">No asset established</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 text-center pointer-events-none">
                                                <span class="text-[9px] font-black text-white uppercase tracking-widest px-4">Transmit New Asset</span>
                                                <p class="text-[7px] text-gray-300 font-bold uppercase tracking-widest px-6">Click area to begin upload</p>
                                            </div>
                                            
                                            <input type="file" id="{{ $content->key }}" name="{{ $content->key }}" 
                                                onchange="previewImage(this, 'preview-{{ $content->key }}', 'preview-container-{{ $content->key }}')"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="bg-indigo-600 rounded-3xl p-8 shadow-xl shadow-indigo-100 overflow-hidden relative group">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all"></div>
                            <h4 class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-4">Branding Protocol</h4>
                            <p class="text-xs font-bold text-white leading-relaxed uppercase tracking-tight">
                                Brand assets are shared between the Administrative portal and Employee uplink. Synchronization is handled automatically.
                            </p>
                            <div class="mt-6 flex items-center gap-2 text-indigo-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <p class="text-[9px] font-black uppercase tracking-widest">Global Assets Engaged</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input, previewId, containerId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    let preview = document.getElementById(previewId);
                    const container = document.getElementById(containerId);
                    
                    if (!preview) {
                        // Create image element if not exists (case where there was "No asset established")
                        container.innerHTML = '';
                        preview = document.createElement('img');
                        preview.id = previewId;
                        preview.className = 'max-w-full max-h-[200px] object-contain shadow-sm';
                        container.appendChild(preview);
                    }
                    
                    preview.src = e.target.result;
                    preview.classList.add('animate-fade-in');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
