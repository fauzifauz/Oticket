<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center gap-4 text-left">
            <a href="{{ route('dashboard') }}" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="font-extrabold text-lg sm:text-2xl text-gray-900 tracking-tight leading-none">
                    {{ __('Initialize Protocol') }}
                </h2>
                <p class="text-[8px] sm:text-xs font-medium text-gray-500 mt-0.5 sm:mt-1 uppercase tracking-widest truncate max-w-[200px] sm:max-w-none">Submit a formal request to the PINS Support</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2rem] sm:rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 sm:px-10 py-6 sm:py-8 border-b border-gray-100 bg-white text-left">
                    <h3 class="text-[10px] sm:text-xs font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        Incident Classification & Data
                    </h3>
                </div>

                <div class="p-5 sm:p-10 text-left" x-data="{ 
                    selectedCategory: '{{ old('category_id', '') }}',
                    subject: '{{ old('subject', '') }}',
                    setQuickIssue(sub, cat) {
                        this.subject = sub;
                        this.selectedCategory = cat;
                        
                        // Visual feedback on the subject field
                        const el = document.getElementById('subject');
                        el.classList.add('ring-4', 'ring-indigo-500/20');
                        setTimeout(() => el.classList.remove('ring-4', 'ring-indigo-500/20'), 500);
                    }
                }">
                    @if ($errors->any())
                        <div class="mb-8 bg-rose-50 border border-rose-100 p-4 rounded-2xl flex items-center gap-3 text-left">
                            <div class="w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <ul class="text-[10px] font-black text-rose-700 uppercase tracking-widest list-none">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-10">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label for="subject" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Incident Headline (Subject)</label>
                                <input type="text" name="subject" id="subject" x-model="subject"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight placeholder:italic @error('subject') border-rose-500 @enderror" 
                                    placeholder="Brief description of the issue..." required>
                            </div>

                            <div class="space-y-3 text-left">
                                <label for="category_id" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1 text-left block">Functional Category</label>
                                <select name="category_id" id="category_id" x-model="selectedCategory"
                                    class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight @error('category_id') border-rose-500 @enderror" required>
                                    <option value="">SELECT PROTOCOL CATEGORY</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Quick Issues Section (Dynamic) -->
                        <div x-show="selectedCategory && selectedCategory !== ''" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-4">
                            <div class="flex items-center gap-2 px-1">
                                <span class="flex h-2 w-2 rounded-full bg-indigo-500"></span>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Quick Predict: Malfungsi Umum Terdeteksi</label>
                            </div>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                                @php
                                    $quickIssues = [
                                        // HARDWARE (ID: 1)
                                        ['label' => 'Printer Rusak', 'subject' => 'PRINTER TIDAK BISA MENCETAK', 'cat' => 1, 'icon' => '🖨️'],
                                        ['label' => 'Laptop Lemot', 'subject' => 'LAPTOP LAMBAT / LEMOT', 'cat' => 1, 'icon' => '💻'],
                                        ['label' => 'Keyboard/Mouse', 'subject' => 'KEYBOARD / MOUSE RUSAK', 'cat' => 1, 'icon' => '🖱️'],
                                        ['label' => 'Monitor Mati', 'subject' => 'MONITOR MATI / BERKEDIP', 'cat' => 1, 'icon' => '🖥️'],
                                        ['label' => 'Baterai Drop', 'subject' => 'BATERAI LAPTOP CEPAT HABIS', 'cat' => 1, 'icon' => '🔋'],
                                        
                                        // SOFTWARE (ID: 2)
                                        ['label' => 'Windows Update', 'subject' => 'MASALAH WINDOWS UPDATE', 'cat' => 2, 'icon' => '⚙️'],
                                        ['label' => 'Office Error', 'subject' => 'MICROSOFT OFFICE ERROR', 'cat' => 2, 'icon' => '📊'],
                                        ['label' => 'Browser Error', 'subject' => 'BROWSER TIDAK BISA DIBUKA', 'cat' => 2, 'icon' => '🌐'],
                                        ['label' => 'PDF Bermasalah', 'subject' => 'PDF READER BERMASALAH', 'cat' => 2, 'icon' => '📄'],
                                        ['label' => 'Install App', 'subject' => 'MASALAH INSTALASI APLIKASI BARU', 'cat' => 2, 'icon' => '📥'],
                                        
                                        // NETWORK (ID: 3)
                                        ['label' => 'Internet Mati', 'subject' => 'GANGGUAN KONEKSI INTERNET (KABEL)', 'cat' => 3, 'icon' => '🔌'],
                                        ['label' => 'WiFi Error', 'subject' => 'WIFI TIDAK BISA CONNECT', 'cat' => 3, 'icon' => '📶'],
                                        ['label' => 'VPN Problem', 'subject' => 'VPN CONNECTION FAILURE', 'cat' => 3, 'icon' => '🛡️'],
                                        ['label' => 'Folder Sharing', 'subject' => 'AKSES FOLDER SHARING BERMASALAH', 'cat' => 3, 'icon' => '📂'],
                                        ['label' => 'Koneksi Lambat', 'subject' => 'KONEKSI LAMBAT (HIGH LATENCY)', 'cat' => 3, 'icon' => '🐢'],
                                        
                                        // SERVER (ID: 4)
                                        ['label' => 'File Server', 'subject' => 'AKSES FILE SERVER TERPUTUS', 'cat' => 4, 'icon' => '🗄️'],
                                        ['label' => 'ERP Error', 'subject' => 'ERP SYSTEM TIDAK BISA DIBUKA', 'cat' => 4, 'icon' => '🏢'],
                                        ['label' => 'Database Error', 'subject' => 'DATABASE CONNECTION ERROR', 'cat' => 4, 'icon' => '💾'],
                                        ['label' => 'Backup Data', 'subject' => 'REQUEST BACKUP DATA', 'cat' => 4, 'icon' => '☁️'],
                                        ['label' => 'Cloud Sync', 'subject' => 'MASALAH SINKRONISASI CLOUD', 'cat' => 4, 'icon' => '🔄'],

                                        // ENHANCE APLIKASI (ID: 5)
                                        ['label' => 'Fitur Baru', 'subject' => 'REQUEST FITUR BARU DI DASHBOARD', 'cat' => 5, 'icon' => '✨'],
                                        ['label' => 'Bug Report', 'subject' => 'PERBAIKAN BUG DI FORM INPUT', 'cat' => 5, 'icon' => '🐛'],
                                        ['label' => 'Export Data', 'subject' => 'REQUEST EXPORT DATA TAMBAHAN', 'cat' => 5, 'icon' => '📤'],
                                        ['label' => 'UI Update', 'subject' => 'UPDATE TAMPILAN UI/UX', 'cat' => 5, 'icon' => '🎨'],
                                        ['label' => 'Loading Lama', 'subject' => 'OPTIMASI KECEPATAN LOADING APLIKASI', 'cat' => 5, 'icon' => '⚡'],

                                        // AKUN & DATA KARYAWAN (ID: 6)
                                        ['label' => 'Akun Terkunci', 'subject' => 'AKUN TERKUNCI / LOGIN FAIL', 'cat' => 6, 'icon' => '🔒'],
                                        ['label' => 'Update Profil', 'subject' => 'UPDATE DATA PROFIL KARYAWAN', 'cat' => 6, 'icon' => '👤'],
                                        ['label' => 'Izin Akses', 'subject' => 'MASALAH IZIN AKSES MODUL', 'cat' => 6, 'icon' => '🔑'],
                                        ['label' => 'User Baru', 'subject' => 'PEMBUATAN AKUN BARU (USER BARU)', 'cat' => 6, 'icon' => '🆕'],
                                        ['label' => 'Resign Account', 'subject' => 'PENONAKTIFAN AKUN KARYAWAN KELUAR', 'cat' => 6, 'icon' => '🚪'],

                                        // RESET PASSWORD (ID: 7)
                                        ['label' => 'Lupa PWD Windows', 'subject' => 'LUPA PASSWORD AKUN WINDOWS/LDAP', 'cat' => 7, 'icon' => '🪟'],
                                        ['label' => 'Lupa PWD Email', 'subject' => 'RESET PASSWORD EMAIL', 'cat' => 7, 'icon' => '📧'],
                                        ['label' => 'Lupa PWD Portal', 'subject' => 'RESET PASSWORD PORTAL KARYAWAN', 'cat' => 7, 'icon' => '🏛️'],
                                        ['label' => 'Lupa PWD Absen', 'subject' => 'RESET PASSWORD APLIKASI ABSENSI', 'cat' => 7, 'icon' => '⏰'],
                                        ['label' => 'Ganti PWD', 'subject' => 'REQUEST GANTI PASSWORD BERKALA', 'cat' => 7, 'icon' => '♻️'],
                                    ];
                                @endphp

                                @foreach($quickIssues as $issue)
                                    <button type="button" 
                                            x-show="selectedCategory == '{{ $issue['cat'] }}'"
                                            @click="setQuickIssue('{{ $issue['subject'] }}', {{ $issue['cat'] }})"
                                            class="flex flex-col items-center justify-center p-3 bg-white border border-gray-100 rounded-2xl hover:bg-indigo-50 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300 group">
                                        <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">{{ $issue['icon'] }}</span>
                                        <span class="text-[8px] font-black text-gray-500 uppercase tracking-tighter group-hover:text-indigo-600 text-center leading-tight">{{ $issue['label'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="description" class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Detailed Forensic Description</label>
                            <textarea name="description" id="description" rows="6" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl py-4 px-6 text-sm font-bold text-gray-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all uppercase tracking-tight placeholder:italic @error('description') border-rose-500 @enderror" 
                                placeholder="PROVIDE FULL CONTEXT, ERROR CODES, OR RELATED CIRCUMSTANCES..." required>{{ old('description') }}</textarea>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-1">Visual Evidence (Optional)</label>
                            <div class="relative group">
                                <input type="file" name="attachment" id="attachment" onchange="previewFile(this)"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30">
                                
                                <div class="w-full bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center transition-all group-hover:border-indigo-300 group-hover:bg-indigo-50/10 relative overflow-hidden h-48">
                                    
                                    <!-- Default Placeholder -->
                                    <div id="upload-placeholder" class="flex flex-col items-center justify-center transition-all duration-300">
                                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 group-hover:text-indigo-500 mb-3 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-[10px] font-black text-gray-400 group-hover:text-indigo-600 uppercase tracking-widest transition-colors">Upload supporting image or document</p>
                                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest mt-1">Maximum file size: 5MB</p>
                                    </div>

                                    <!-- File Preview -->
                                    <div id="file-preview" class="hidden absolute inset-0 w-full h-full bg-gray-50 flex flex-col items-center justify-center z-20 pointer-events-none">
                                        <img id="preview-img" class="h-28 w-auto object-contain mb-3 rounded-lg shadow-md border border-gray-100 hidden">
                                        
                                        <!-- Generic File Icon (for non-images) -->
                                        <div id="generic-file-icon" class="hidden w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-500 mb-3">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>

                                        <div class="text-center px-6 w-full">
                                            <p id="file-name-display" class="text-[10px] font-black text-gray-800 uppercase tracking-widest truncate w-full"></p>
                                            <p class="text-[8px] font-bold text-indigo-500 uppercase tracking-widest mt-1 bg-indigo-50 px-2 py-1 rounded-full inline-block">Click to change file</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <script>
                                function previewFile(input) {
                                    const placeholder = document.getElementById('upload-placeholder');
                                    const preview = document.getElementById('file-preview');
                                    const previewImg = document.getElementById('preview-img');
                                    const genericIcon = document.getElementById('generic-file-icon');
                                    const nameDisplay = document.getElementById('file-name-display');
                                    
                                    if (input.files && input.files[0]) {
                                        const file = input.files[0];
                                        nameDisplay.textContent = file.name;
                                        
                                        // Show preview container, hide placeholder
                                        placeholder.classList.add('opacity-0');
                                        preview.classList.remove('hidden');
                                        
                                        if (file.type.startsWith('image/')) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                previewImg.src = e.target.result;
                                                previewImg.classList.remove('hidden');
                                                genericIcon.classList.add('hidden');
                                            }
                                            reader.readAsDataURL(file);
                                        } else {
                                            previewImg.classList.add('hidden');
                                            genericIcon.classList.remove('hidden');
                                        }
                                    } else {
                                        // Reset if cancelled
                                        placeholder.classList.remove('opacity-0');
                                        preview.classList.add('hidden');
                                    }
                                }
                            </script>
                        </div>

                        <div class="pt-6 border-t border-gray-50">
                            <button type="submit" class="w-full py-5 bg-indigo-600 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-indigo-700 shadow-2xl shadow-indigo-100 transition-all duration-300 flex items-center justify-center gap-3 transform hover:-translate-y-1 active:translate-y-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                Deploy Support Request
                            </button>
                            <p class="mt-4 text-center text-[9px] font-bold text-gray-400 uppercase tracking-[0.1em]">By submitting, you agree to the IT Security and Operational SLA terms.</p>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-6 sm:mt-8 grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-6 text-left">
                <div class="bg-indigo-50 rounded-2xl p-4 sm:p-6 border border-indigo-100">
                    <h5 class="text-[8px] sm:text-[9px] font-black text-indigo-700 uppercase tracking-widest mb-1 sm:mb-2 text-left">Automated Routing</h5>
                    <p class="text-[9px] sm:text-[10px] font-bold text-indigo-600/70 uppercase tracking-tight leading-tight sm:leading-relaxed text-left">Your request will be instantly analyzed and routed to the corresponding department.</p>
                </div>
                <div class="bg-emerald-50 rounded-2xl p-4 sm:p-6 border border-emerald-100 text-left">
                    <h5 class="text-[8px] sm:text-[9px] font-black text-emerald-700 uppercase tracking-widest mb-1 sm:mb-2">Priority Assurance</h5>
                    <p class="text-[9px] sm:text-[10px] font-bold text-emerald-600/70 uppercase tracking-tight leading-tight sm:leading-relaxed">SLA timers activate immediately upon deployment based on your selection.</p>
                </div>
                <div class="bg-rose-50 rounded-2xl p-4 sm:p-6 border border-rose-100 text-left">
                    <h5 class="text-[8px] sm:text-[9px] font-black text-rose-700 uppercase tracking-widest mb-1 sm:mb-2">Visual Evidence</h5>
                    <p class="text-[9px] sm:text-[10px] font-bold text-rose-600/70 uppercase tracking-tight leading-tight sm:leading-relaxed">Providing screenshots significantly reduces initial response time and forensics.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
