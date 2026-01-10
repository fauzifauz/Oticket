<x-guest-layout>
    <!-- Page Title -->
    <div class="mb-6 sm:mb-8 text-center text-left">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Password Recovery</h2>
        <p class="text-sm sm:text-base text-gray-600">Enter your email and we'll send you a recovery link</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6 text-left">
        @csrf
        <input type="hidden" name="source" value="{{ request()->query('source', 'admin') }}">

        <!-- Email Address -->
        <div class="relative">
            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="block w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 text-sm sm:text-base"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <button type="submit" 
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm sm:text-base">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send Recovery Link
                </span>
            </button>
        </div>

        <div class="text-center pt-2">
            @php
                $returnRoute = request()->query('source') === 'employee' ? route('employee.login') : route('login');
            @endphp
            <a href="{{ $returnRoute }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors uppercase tracking-widest">
                Return to Login
            </a>
        </div>
    </form>
</x-guest-layout>
