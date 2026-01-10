<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Terima kasih telah melakukan registrasi. Akun Anda saat ini sedang menunggu persetujuan dari Administrator. Anda akan dapat masuk setelah akun Anda diaktifkan.') }}
    </div>

    <div class="flex items-center justify-center mt-6">
        <a href="{{ route('employee.login') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            {{ __('Kembali ke Login') }}
        </a>
    </div>
</x-guest-layout>
