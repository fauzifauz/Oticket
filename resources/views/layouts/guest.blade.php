<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .gradient-accent {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.4); }
                50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.6); }
            }
            .pulse-glow {
                animation: pulse-glow 3s ease-in-out infinite;
            }
        </style>
    </head>
    @php
        $bgImage = isset($cms['login_bg_image']) ? asset('storage/' . $cms['login_bg_image']) . '?v=' . time() : asset('images/default-bg.jpg');
    @endphp
    <body class="font-sans antialiased">
        <div class="min-h-screen flex relative">
            <!-- Mobile Background (Absolute) -->
            <div class="absolute inset-0 lg:hidden z-0" 
                 style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center;">
                 <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-[2px]"></div>
            </div>

            <!-- Left Side - Branding/Illustration (Hidden on mobile) -->
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden z-10" 
                 style="background-image: url('{{ $bgImage }}'); background-size: cover; background-position: center;">
                
                <div class="absolute inset-0 bg-indigo-900/40 backdrop-blur-[2px]"></div>
                
                <div class="relative z-10 flex flex-col justify-center items-center w-full px-12 text-white">
                    <div class="mb-12"></div> <!-- Spacer for branding -->

                    <!-- Features List -->
                    <div class="space-y-6 max-w-md">
                        <div class="flex items-start gap-4 float-animation" style="animation-delay: 0s;">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Fast Response</h3>
                                <p class="text-indigo-100 text-sm">Get quick solutions to your IT problems</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 float-animation" style="animation-delay: 0.5s;">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Track Progress</h3>
                                <p class="text-indigo-100 text-sm">Monitor your tickets in real-time</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 float-animation" style="animation-delay: 1s;">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">Secure System</h3>
                                <p class="text-indigo-100 text-sm">Your data is protected with encryption</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-6 md:p-8 bg-transparent lg:bg-gray-50 relative z-10">
                <div class="w-full max-w-[85%] sm:max-w-md">
                    <!-- Login Card -->
                    <div class="bg-white/90 backdrop-blur-lg lg:bg-white rounded-2xl shadow-2xl p-5 sm:p-8 md:p-10 border border-gray-100/50 lg:border-gray-100">
                        {{ $slot }}
                    </div>

                    <!-- Back to Home Link -->
                    <div class="mt-6 text-center">
                        <a href="/" class="inline-flex items-center gap-2 text-sm text-gray-200 lg:text-gray-600 hover:text-white lg:hover:text-indigo-600 transition-colors font-medium shadow-sm lg:shadow-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
