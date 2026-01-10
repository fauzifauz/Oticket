<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cms['hero_title'] ?? 'OTICKET - Solusi Permintaan IT Terpusat' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fade-in-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-white text-slate-900 selection:bg-indigo-100 selection:text-indigo-700 overflow-x-hidden">
    <!-- Navbar -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = window.pageYOffset > 20"
         :class="scrolled ? 'glass-nav shadow-lg shadow-indigo-500/5' : 'bg-transparent'"
         class="fixed w-full z-50 top-0 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <div class="flex items-center">
                    <a href="/" class="flex items-center gap-2 group">
                        @if(isset($cms['navbar_logo']) && $cms['navbar_logo'])
                             <img src="{{ asset('storage/' . $cms['navbar_logo']) }}?v={{ time() }}" alt="Logo" class="h-8 sm:h-10 w-auto group-hover:scale-105 transition-transform duration-300">
                        @else
                            <span class="font-extrabold text-xl sm:text-2xl tracking-tighter text-indigo-600">OTICKET</span>
                        @endif
                    </a>
                </div>
                
                <!-- Desktop Links -->
                <div class="hidden md:flex items-center gap-8">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-xs font-black text-slate-600 hover:text-indigo-600 transition uppercase tracking-widest">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-black text-slate-600 hover:text-indigo-600 transition uppercase tracking-widest">{{ __('Log in') }}</a>
                            <a href="{{ route('employee.login') }}" class="bg-indigo-600 text-white text-[10px] font-black py-3 px-6 rounded-full shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all duration-300 uppercase tracking-widest">
                                {{ __('Portal Karyawan') }}
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 focus:outline-none transition-all duration-300">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 -translate-y-10" 
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-10"
             class="md:hidden glass-nav border-t border-indigo-50/50 px-4 pt-4 pb-8 space-y-3 shadow-2xl">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="flex items-center px-4 py-4 text-xs font-black text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-2xl uppercase tracking-widest transition-all">{{ __('Dashboard') }}</a>
                @else
                    <div class="flex flex-col gap-3 px-4 pt-2">
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('employee.login') }}" class="flex items-center justify-center w-full px-4 py-3.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all">
                            {{ __('Portal Karyawan') }}
                        </a>
                    </div>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative min-h-[90vh] flex items-center pt-20 overflow-hidden">
        <!-- Dynamic Background -->
        @if(isset($cms['hero_bg_image']) && $cms['hero_bg_image'])
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('storage/' . $cms['hero_bg_image']) }}?v={{ time() }}" alt="Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-br from-white via-white/95 to-white/40"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-indigo-50 via-white to-blue-50">
                <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(#6366f1 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
            </div>
        @endif
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <span class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full text-[10px] sm:text-xs font-black uppercase tracking-[0.2em] mb-6 animate-pulse">
                    Professional IT Support
                </span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 mb-6 fade-in-up leading-[1.1] tracking-tight">
                    {!! $cms['hero_title'] ?? 'Solusi Permintaan IT <br><span class="text-indigo-600">Terpusat & Efisien</span>' !!}
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-slate-500 mb-10 fade-in-up delay-100 leading-relaxed max-w-2xl">
                    {{ $cms['hero_description'] ?? 'Kelola tiket bantuan IT Anda dalam satu platform yang mudah digunakan. Tingkatkan produktivitas tim dengan sistem yang cepat dan transparan.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 fade-in-up delay-200">
                    <a href="{{ route('employee.login') }}" class="inline-flex items-center justify-center bg-indigo-600 text-white font-black py-4 px-8 sm:px-10 rounded-2xl shadow-2xl shadow-indigo-500/25 hover:bg-indigo-700 hover:-translate-y-1 transition-all duration-300 text-xs sm:text-sm uppercase tracking-widest group">
                        {!! $cms['hero_button_text'] ?? 'Mulai Lapor Sekarang' !!}
                        <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- How It Works -->
    <section class="py-24 sm:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 sm:mb-24 fade-in-up">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                    {{ $cms['features_section_title'] ?? 'Cara Kerja' }}
                </h2>
                <div class="w-20 h-1.5 bg-indigo-600 mx-auto rounded-full mb-6"></div>
                <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto font-medium">
                    {{ $cms['features_section_subtitle'] ?? 'Proses sederhana untuk penyelesaian masalah Anda dalam hitungan menit.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                <!-- Step 1 -->
                <div class="group relative p-8 rounded-[2rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 fade-in-up delay-100 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full -mr-16 -mt-16 group-hover:bg-indigo-100/50 transition-colors"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            {{ $cms['step_1_icon'] ?? '📝' }}
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">{{ $cms['step_1_title'] ?? '1. Laporkan' }}</h3>
                        <p class="text-slate-500 leading-relaxed text-sm font-medium">
                            {{ $cms['step_1_desc'] ?? 'Ajukan keluhan atau permintaan inventaris IT Anda melalui formulir tiket yang tersedia dengan detail lengkap.' }}
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="group relative p-8 rounded-[2rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 fade-in-up delay-200 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full -mr-16 -mt-16 group-hover:bg-indigo-100/50 transition-colors"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            {{ $cms['step_2_icon'] ?? '⚙️' }}
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">{{ $cms['step_2_title'] ?? '2. Ditangani' }}</h3>
                        <p class="text-slate-500 leading-relaxed text-sm font-medium">
                            {{ $cms['step_2_desc'] ?? 'Tim support IT profesional kami akan segera memproses, memprioritaskan, dan menangani permintaan Anda.' }}
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="group relative p-8 rounded-[2rem] bg-white border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 fade-in-up delay-300 overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full -mr-16 -mt-16 group-hover:bg-indigo-100/50 transition-colors"></div>
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            {{ $cms['step_3_icon'] ?? '✅' }}
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">{{ $cms['step_3_title'] ?? '3. Selesai' }}</h3>
                        <p class="text-slate-500 leading-relaxed text-sm font-medium">
                            {{ $cms['step_3_desc'] ?? 'Dapatkan notifikasi real-time dan konfirmasi setelah masalah terselesaikan dengan tuntas.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="font-black text-2xl tracking-tighter text-white">OTICKET</span>
                    </div>
                    <p class="text-slate-400 font-medium max-w-sm leading-relaxed">
                        Sistem manajemen tiket IT terpadu untuk meningkatkan efisiensi dan transparansi operasional perusahaan.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-400 mb-6 font-bold">Menu</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Admin Login</a></li>
                        <li><a href="{{ route('employee.login') }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Portal Karyawan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-indigo-400 mb-6 font-bold">Legal</h4>
                    <ul class="space-y-4">
                        <li><a href="{{ $cms['footer_privacy_link'] ?? '#' }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Privacy Policy</a></li>
                        <li><a href="{{ $cms['footer_terms_link'] ?? '#' }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Terms of Service</a></li>
                        <li><a href="{{ $cms['footer_contact_link'] ?? '#' }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Contact Support</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-6">
                <p class="text-xs text-slate-500 font-black uppercase tracking-widest text-center sm:text-left leading-none">
                    &copy; {{ date('Y') }} {{ $cms['footer_company_name'] ?? 'OTICKET' }}. All rights reserved.
                </p>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest leading-none">System Active</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
