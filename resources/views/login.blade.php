@extends('master')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-700">
        <div
            class="p-12  bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700  max-w-2xl mx-auto">
            <div class="max-w-xl mx-auto">
                <h2 class="text-center text-2xl font-bold pb-1">SIGN IN</h2>
                <p class="text-center text-gray-600 dark:text-gray-300">Mulai Perjalanan Magangmu disini</p>
                <hr class="my-4 border-gray-300 dark:border-gray-600">

                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-red-900 dark:text-red-300"
                        role="alert">
                        <span class="font-medium">Oops! Something went wrong:</span> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('actionlogin') }}" method="post">
                    @csrf
                    <div class="mb-4">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Email" required>
                    </div>

                    <div class="mb-4">
                        <label for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Password" required>
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                        Masuk
                    </button>
                </form>

                <hr class="my-4 border-gray-300 dark:border-gray-600">
                <p class="text-center text-gray-600 dark:text-gray-300">Belum punya akun? <a href="{{ route('register') }}"
                        class="text-blue-600 hover:underline dark:text-blue-400">Daftar</a> sekarang!</p>
            </div>
        </div>
    </div>
@endsection
