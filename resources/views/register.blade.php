@extends('master')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
        <div
            class="w-full max-w-4xl bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2">

            <!-- Ilustrasi -->
            <div class="hidden md:flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600">
                <img src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/authentication/illustration.svg"
                    alt="Illustration" class="w-3/4">
            </div>

            <!-- Formulir -->
            <div class="p-8 sm:p-12">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-white">Form Pendaftaran</h2>
                <p class="mt-1 text-center text-gray-500 dark:text-gray-300">Isi data dengan benar untuk mendaftar</p>
                <hr class="my-6 border-gray-300 dark:border-gray-600">

                @if (session('message'))
                    <div id="alert-border-success"
                        class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-green-900 dark:text-green-300 dark:border-green-800"
                        role="alert">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M16.707 5.293a1 1 0 010 1.414L8.414 15H6v-2.414l8.293-8.293a1 1 0 011.414 0z"></path>
                        </svg>
                        <div class="ms-3 text-sm font-medium">
                            {{ session('message') }}
                        </div>
                        <button type="button"
                            class="ms-auto -mx-1.5 -my-1.5 bg-green-50 dark:bg-green-900 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 dark:hover:bg-green-800 inline-flex items-center justify-center h-8 w-8"
                            data-dismiss-target="#alert-border-success" aria-label="Close">
                            <span class="sr-only">Dismiss</span>
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                <form action="{{ route('actionregister') }}" method="post" class="space-y-5">
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
                        <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fa fa-user mr-1"></i>Username
                        </label>
                        <input type="text" name="username" id="username" placeholder="your_username"
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

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            <i class="fa fa-briefcase mr-1"></i>Role
                        </label>
                        <select name="role" id="role"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-200 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                        <i class="fa fa-user-plus mr-1"></i> Daftar
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="font-medium text-blue-600 hover:underline dark:text-blue-400">Masuk sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Script Flowbite Alert -->
    <script>
        document.querySelectorAll('[data-dismiss-target]').forEach(button => {
            button.addEventListener('click', function() {
                const target = document.querySelector(this.getAttribute('data-dismiss-target'));
                target.classList.add('hidden');
            });
        });
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const role = document.getElementById('role').value;

            let errorMessage = '';

            if (!email || !username || !password || !role) {
                errorMessage = 'Semua field harus diisi!';
            } else if (password.length < 8) {
                errorMessage = 'Password minimal harus terdiri dari 8 karakter!';
            }

            if (errorMessage) {
                e.preventDefault(); // Stop form submission
                alert(errorMessage);
            }
        });
    </script>
@endsection
