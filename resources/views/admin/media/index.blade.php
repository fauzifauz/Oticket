<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-left gap-4">
            <div>
                <h2 class="font-extrabold text-xl sm:text-2xl text-gray-900 tracking-tight">
                    {{ __('Media Registry') }}
                </h2>
                <p class="text-[10px] sm:text-xs font-medium text-gray-500 mt-1 uppercase tracking-widest">Asset management and system audit trail of blobs</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 sm:px-4 py-1.5 bg-gray-900 text-gray-100 border border-gray-800 rounded-full text-[9px] sm:text-[10px] font-black uppercase tracking-widest shadow-lg shadow-gray-200">
                    S3 / Local Storage Protocol
                </span>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
        open: false, 
        currentMedia: null,
        showAll: false,
        showAllAttachments: false,
        openPreview(url, type, name) {
            this.currentMedia = { url, type, name };
            this.open = true;
        }
    }" @keydown.escape.window="open = false" class="py-12 bg-gray-50/50 text-left">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3 animate-fade-in-down">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-xs font-black text-emerald-700 uppercase tracking-widest">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Brand Architecture Assets -->
            <div class="space-y-6">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm animate-pulse"></span>
                            Brand Architecture Assets
                        </h3>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Directly synchronized with Landing Page settings</p>
                    </div>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm border border-indigo-100">
                        {{ count($landingPageMedia) }} Configurations Active
                    </span>
                </div>

                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-4 gap-4">
                    <template x-for="(media, index) in {{ json_encode($landingPageMedia) }}" :key="index">
                        <div class="relative group bg-white rounded-3xl overflow-hidden border border-gray-100 transition-all hover:shadow-2xl hover:shadow-indigo-100/50 p-2">
                            <!-- File Preview -->
                            <div class="aspect-square bg-gray-50 rounded-2xl flex items-center justify-center overflow-hidden relative">
                                <template x-if="media.url && media.type === 'image'">
                                    <img :src="media.url" :alt="media.name" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                </template>
                                <template x-if="!media.url || media.type !== 'image'">
                                    <div class="text-gray-300 flex flex-col items-center px-4 text-center">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span class="text-[8px] font-black uppercase tracking-widest text-gray-400" x-text="media.url ? 'Electronic Doc' : 'Empty Configuration'"></span>
                                    </div>
                                </template>

                                <div class="absolute top-1.5 left-1.5 sm:top-3 sm:left-3">
                                    <span class="bg-indigo-600 text-white text-[6px] sm:text-[8px] font-black px-1.5 py-0.5 sm:px-2 rounded sm:rounded-md uppercase tracking-widest shadow-lg shadow-indigo-100">
                                        <span class="sm:hidden">ON</span>
                                        <span class="hidden sm:inline">Active Settings</span>
                                    </span>
                                </div>

                                <div class="absolute inset-0 bg-gray-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>

                            <!-- File Info & Actions -->
                            <div class="p-2 sm:p-4 space-y-2 sm:space-y-3">
                                <div class="space-y-0.5 sm:space-y-1">
                                    <p class="text-[8px] sm:text-[9px] font-black text-indigo-600 uppercase tracking-widest truncate" x-text="media.label"></p>
                                    <p class="text-[9px] sm:text-[10px] font-black text-gray-900 truncate uppercase tracking-tight" :title="media.name" x-text="media.name"></p>
                                    <div class="flex items-center justify-between hidden sm:flex">
                                        <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest" x-text="media.size"></span>
                                        <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest" x-text="media.last_modified"></span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="if(media.url) openPreview(media.url, media.type, media.label)" 
                                        :class="media.url ? 'bg-gray-50 hover:bg-indigo-600 hover:text-white' : 'opacity-50 cursor-not-allowed bg-gray-100'"
                                        class="flex-1 py-1.5 sm:py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 border border-gray-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span class="hidden sm:inline">Inspect</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- System Blob Registry -->
            @if(count($systemAssets) > 0)
                <div class="space-y-6 pt-12">
                    <div class="flex items-center justify-between px-1 border-t border-gray-100 pt-8">
                        <div>
                            <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                System Blob Registry
                            </h3>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Legacy or unreferenced media assets remaining on server</p>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm">
                            {{ count($systemAssets) }} Blobs
                        </span>
                    </div>

                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-4 gap-4">
                        @foreach($systemAssets as $media)
                            <div class="relative group bg-white rounded-2xl overflow-hidden border border-gray-100 transition-all p-1.5 opacity-70 hover:opacity-100">
                                <div class="aspect-square bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden relative">
                                    @if($media['type'] === 'image')
                                        <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" class="object-cover w-full h-full" loading="lazy">
                                    @else
                                        <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                    @endif
                                    <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <button @click="openPreview('{{ $media['url'] }}', '{{ $media['type'] }}', '{{ $media['name'] }}')" class="p-1.5 bg-white rounded-lg text-gray-900 shadow-xl hover:scale-110 transition-transform"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
                                        <form action="{{ route('admin.media.destroy') }}" method="POST" onsubmit="return confirm('Permanently delete this unreferenced blob?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" value="{{ $media['path'] }}">
                                            <button type="submit" class="p-1.5 bg-rose-600 rounded-lg text-white shadow-xl hover:scale-110 transition-transform"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- User Attachments -->
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row items-center justify-between px-1 gap-4">
                    <div>
                        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Protocol User Evidence
                        </h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1">Attachments uploaded by users within the incident lifecycle</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <form action="{{ route('admin.media.index') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                            <div class="relative flex-1 sm:w-64">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by employee/ID..." 
                                    class="w-full py-1.5 px-3 bg-white border border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold uppercase focus:ring-1 focus:ring-emerald-500">
                            </div>
                            <select name="type" class="py-1.5 px-3 bg-white border border-gray-200 rounded-lg text-[10px] sm:text-xs font-bold uppercase focus:ring-1 focus:ring-emerald-500" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                                <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents</option>
                            </select>
                            <button type="submit" class="hidden sm:inline-flex px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all">
                                Filter
                            </button>
                        </form>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg uppercase tracking-widest shadow-sm shrink-0">
                            {{ count($userAttachments) }} Evidences
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-4 lg:grid-cols-4 gap-4">
                        <template x-for="(media, index) in {{ json_encode($userAttachments) }}" :key="index">
                            <div x-show="showAllAttachments || index < 8"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="relative group bg-white rounded-3xl overflow-hidden border border-gray-100 transition-all hover:shadow-2xl hover:shadow-emerald-100/50 p-2">
                                <div class="aspect-square bg-gray-50 rounded-2xl flex items-center justify-center overflow-hidden relative">
                                    <template x-if="media.type === 'image'">
                                        <img :src="media.url" :alt="media.name" class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                    </template>
                                    <template x-if="media.type !== 'image'">
                                        <div class="text-gray-300 flex flex-col items-center">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <span class="text-[8px] font-black mt-2 uppercase tracking-widest text-gray-400">Protocol Evidence</span>
                                        </div>
                                    </template>

                                    <div class="absolute inset-0 bg-gray-900/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                </div>

                                <!-- File Info & Actions -->
                                <div class="p-2 sm:p-4 space-y-2 sm:space-y-3">
                                    <div class="space-y-0.5 sm:space-y-1">
                                        <p class="text-[9px] sm:text-[10px] font-black text-gray-900 truncate uppercase tracking-tight" :title="media.name" x-text="media.name"></p>
                                        <div class="flex flex-col gap-0.5 mt-1">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-2.5 h-2.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                <span class="text-[8px] text-indigo-600 font-black uppercase truncate" x-text="media.employee"></span>
                                            </div>
                                            <a :href="media.ticket_id ? '/admin/tickets/' + media.ticket_id : '#'" class="flex items-center gap-1 hover:text-indigo-600 transition-colors">
                                                <svg class="w-2.5 h-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M12 7h.01M12 11h.01M12 15h.01M17 7h.01M17 11h.01M17 15h.01"></path></svg>
                                                <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest" x-text="'TICKET #' + media.ticket_id"></span>
                                            </a>
                                        </div>
                                        <div class="flex items-center justify-between pt-1">
                                            <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest" x-text="media.size"></span>
                                            <span class="text-[8px] text-gray-400 font-bold uppercase tracking-widest" x-text="new Date(media.last_modified).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'})"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="openPreview(media.url, media.type, media.name)" 
                                            class="flex-1 py-1.5 sm:py-2 bg-gray-50 hover:bg-emerald-600 hover:text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2 border border-gray-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span class="hidden sm:inline">Verify</span>
                                        </button>
                                        <form :action="'{{ route('admin.media.destroy') }}'" method="POST" onsubmit="return confirm('STOP: Deleting evidence affects audit integrity. Proceed?');" class="shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="path" :value="media.path">
                                            <button type="submit" class="w-8 h-8 bg-white border border-rose-100 rounded-xl flex items-center justify-center text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="{{ count($userAttachments) }} === 0">
                            <div class="col-span-full py-20 bg-white rounded-3xl border border-dashed border-gray-200 text-center">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">No evidence assets have been recorded</p>
                            </div>
                        </template>
                    </div>

                    <!-- View All Button -->
                    <div x-show="{{ count($userAttachments) }} > 8" class="flex justify-center pt-4">
                        <button @click="showAllAttachments = !showAllAttachments" 
                            class="inline-flex items-center px-6 py-3 bg-white border-2 border-emerald-100 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all shadow-lg shadow-emerald-50 gap-2">
                            <svg class="w-4 h-4" :class="{'rotate-180': showAllAttachments}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                            <span x-text="showAllAttachments ? 'Show Less' : 'View All (' + {{ count($userAttachments) }} + ' Evidences)'"></span>
                        </button>
                    </div>
                </div>
            </div>

        <!-- Preview Modal -->
        <div x-show="open" 
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
            x-cloak>
            <!-- Backdrop -->
            <div x-show="open" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm"
                @click="open = false"></div>

            <!-- Modal Content -->
            <div x-show="open" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-full overflow-hidden flex flex-col">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white z-10">
                    <div>
                        <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest" x-text="currentMedia?.name"></h4>
                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-0.5" x-text="currentMedia?.type === 'image' ? 'Image Asset' : 'Electronic Document'"></p>
                    </div>
                    <button @click="open = false" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 overflow-auto bg-gray-50 flex items-center justify-center p-4 min-h-[50vh]">
                    <template x-if="currentMedia?.type === 'image'">
                        <img :src="currentMedia.url" :alt="currentMedia.name" class="max-w-full max-h-full object-contain rounded-xl shadow-lg">
                    </template>
                    <template x-if="currentMedia?.type === 'document'">
                        <div class="w-full h-full flex flex-col items-center justify-center gap-6">
                            <iframe :src="currentMedia.url" class="w-full h-[70vh] rounded-xl border border-gray-200 shadow-sm" frameborder="0"></iframe>
                            <a :href="currentMedia.url" download class="inline-flex items-center px-6 py-2.5 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-800 transition-all shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Protocol Asset
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
