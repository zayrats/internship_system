<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ayo Magang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.4/dist/tailwind.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <style>
        /* Animasi navbar muncul */
        .fade-in-down {
            animation: fadeInDown 0.5s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Blur efek kaca */
        .glass-bg {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.312);
        }

        .dark .glass-bg {
            background-color: rgba(31, 41, 55, 0.8);
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans transition-all duration-300">

    <!-- Navbar -->
    <nav class="glass-bg fade-in-down sticky top-0 z-50 shadow-md">
        <div class="max-w-screen-xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <img src="https://kompaspedia.kompas.id/wp-content/uploads/2020/07/logo_Politeknik-Elektronika-Negeri-Surabaya-thumb.png"
                    class="h-8" alt="Logo" />
                <span class="text-xl font-extrabold tracking-wide text-white-700 dark:text-white-400">Siap Magang</span>
            </a>
            <div class="flex items-center space-x-3">
                @if (Auth::check())
                    <a href="{{ route('profile') }}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-all duration-300 text-sm font-medium">
                        <i class="fas fa-user-circle mr-1"></i> Profil
                    </a>
                    <a href="{{ route('logout') }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-all duration-300 text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-all duration-300 text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                    </a>
                @endif
            </div>
        </div>

        <!-- Menu -->
        <div class="max-w-screen-xl mx-auto px-4 pb-2 hidden md:flex justify-between items-center">
            <ul class="flex space-x-6 text-sm font-medium">
                <li><a href="{{ route('home') }}"
                        class="transition-all duration-300 hover:text-blue-600 {{ Request::routeIs('home') ? 'text-blue-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                        Beranda</a></li>
                <li><a href="{{ route('internship') }}"
                        class="transition-all duration-300 hover:text-blue-600 {{ Request::routeIs('internship') ? 'text-blue-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                        Informasi KP</a></li>
                <li><a href="{{ route('history') }}"
                        class="transition-all duration-300 hover:text-blue-600 {{ Request::routeIs('history') ? 'text-blue-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                        Pengajuan KP</a></li>
                <li><a href="{{ route('internexperience') }}"
                        class="transition-all duration-300 hover:text-blue-600 {{ Request::routeIs('internexperience') ? 'text-blue-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                        Laporan KP</a></li>
                <li><a href="{{ route('qna.index') }}"
                        class="transition-all duration-300 hover:text-blue-600 {{ Request::routeIs('qna.index') ? 'text-blue-600 font-bold' : 'text-gray-700 dark:text-gray-300' }}">
                        Forum Tanya Jawab</a></li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <main class="min-h-screen mt-6 px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 mt-10">
        <div class="max-w-screen-xl mx-auto p-4 md:py-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <a href="/" class="flex items-center mb-4 sm:mb-0">
                    <img src="https://kompaspedia.kompas.id/wp-content/uploads/2020/07/logo_Politeknik-Elektronika-Negeri-Surabaya-thumb.png"
                        class="h-8 me-3" alt="Logo" />
                    <span class="text-xl font-bold dark:text-white">Siap Magang</span>
                </a>
                <ul class="flex flex-wrap items-center text-sm text-gray-500 dark:text-gray-400">
                    <li><a href="#" class="hover:underline me-4">Tentang</a></li>
                    <li><a href="#" class="hover:underline me-4">Privasi</a></li>
                    <li><a href="#" class="hover:underline me-4">Kontak</a></li>
                    <li><a href="#" class="hover:underline">Github</a></li>
                </ul>
            </div>
            <hr class="my-6 border-gray-300 dark:border-gray-600" />
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">
                © {{ date('Y') }} <a href="/" class="hover:underline font-semibold">Surya Tegar Prasetya™</a>
            </p>
        </div>
    </footer>

</body>

</html>
