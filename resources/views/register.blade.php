@extends('master')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-700">
    <div class="w-full max-w-lg bg-white border border-gray-200 rounded-lg shadow-lg p-6 dark:bg-gray-800 dark:border-gray-700">
        <h2 class="text-center text-2xl font-bold text-gray-900 dark:text-white">FORM REGISTER USER</h2>
        <hr class="my-4 border-gray-300 dark:border-gray-600">

        @if (session('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 dark:bg-green-900 dark:text-green-300" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <form action="{{ route('actionregister') }}" method="post">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa fa-envelope"></i> Email
                </label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Email" required>
            </div>

            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa fa-user"></i> Username
                </label>
                <input type="text" name="username" id="username" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Username" required>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa fa-key"></i> Password
                </label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Password" required>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <i class="fa fa-address-book"></i> Role
                </label>
                <select name="role" id="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                <i class="fa fa-user"></i> Register
            </button>
        </form>

        <hr class="my-4 border-gray-300 dark:border-gray-600">
        <p class="text-center text-gray-600 dark:text-gray-300">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline dark:text-blue-400">Login</a> sekarang!
        </p>
    </div>
</div>

@endsection
