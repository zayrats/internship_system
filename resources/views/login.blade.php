@extends('master')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
    <div class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- Ilustrasi -->
        <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600">
            <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/authentication/illustration.svg" alt="Login Illustration" class="w-3/4">
        </div>

        <!-- Form Login -->
        <div class="p-8 sm:p-12">
            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white">Selamat Datang 👋</h2>
            <p class="mt-1 text-center text-gray-500 dark:text-gray-300">Masuk untuk memulai perjalanan magangmu</p>
            <hr class="my-6 border-gray-300 dark:border-gray-600">

            @if (session('error'))
                <div id="alert-border-error" class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-red-900 dark:text-red-300 dark:border-red-800" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 13a1 1 0 01-1 1H3a1 1 0 010-2h14a1 1 0 011 1zM18 9a1 1 0 01-1 1H3a1 1 0 010-2h14a1 1 0 011 1z" clip-rule="evenodd" /></svg>
                    <div class="ms-3 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 dark:bg-red-900 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 dark:hover:bg-red-800 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#alert-border-error" aria-label="Close">
                        <span class="sr-only">Dismiss</span>
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            @endif

            <form action="{{ route('actionlogin') }}" method="post" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fa fa-envelope mr-1"></i>Email
                    </label>
                    <input type="email" name="email" id="email" placeholder="your@email.com"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fa fa-key mr-1"></i>Password
                    </label>
                    <input type="password" name="password" id="password" placeholder="********"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        required>
                </div>

                <button type="submit"
                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                    <i class="fa fa-sign-in-alt mr-1"></i> Masuk
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="font-medium text-blue-600 hover:underline dark:text-blue-400">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>

<!-- Script Flowbite Alert -->
<script>
    document.querySelectorAll('[data-dismiss-target]').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('data-dismiss-target'));
            target.classList.add('hidden');
        });
    });
</script>

@endsection
