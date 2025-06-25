<!DOCTYPE html>
<html lang="id">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dosen - Ayo Magang</title>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100">
    <div class="flex h-full min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="w-64 bg-white dark:bg-gray-800 shadow-md fixed inset-y-0 left-0 transform md:translate-x-0 transition-transform duration-300 ease-in-out z-40">
            <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700 text-white">
                <img src="https://kompaspedia.kompas.id/wp-content/uploads/2020/07/logo_Politeknik-Elektronika-Negeri-Surabaya-thumb.png"
                    alt="Logo" class="h-10 mr-2">
                <span class=" text-lg font-bold">Dosen Koordinator KP</span>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('dosen.dashboard') }}"
                    class="flex items-center p-2 rounded hover:bg-blue-100 dark:hover:bg-gray-700 transition text-white">
                    <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('dosen.monitoring') }}"
                    class="flex items-center p-2 rounded hover:bg-blue-100 dark:hover:bg-gray-700 transition text-white">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 10h18M9 21V3m6 18V3"></path>
                    </svg>
                    Monitoring
                </a>
                <a href="{{ route('dosen.companies') }}"
                    class="flex items-center p-2 rounded hover:bg-blue-100 dark:hover:bg-gray-700 transition text-white">
                    <svg class="w-5 h-5 mr-3 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    Manajemen Perusahaan
                </a>
                <a href="{{ route('sidang.index') }}"
                    class="flex items-center p-2 rounded hover:bg-blue-100 dark:hover:bg-gray-700 transition text-white">
                    <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M8 7V3M16 7V3M4 11h16M5 20h14a2 2 0 002-2V7H3v11a2 2 0 002 2z"></path>
                    </svg>
                    Jadwal Sidang
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center p-2 rounded bg-red-500 text-white hover:bg-red-600 transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <main class=" bg-gray-500 p-6 flex-1 overflow-y-auto">
            @yield('content')
        </main>

    </div>
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 ml-64">
        <div class="max-w-screen-xl mx-auto p-4 md:py-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <a href="/" class="flex items-center mb-4 sm:mb-0">
                    <img src="https://kompaspedia.kompas.id/wp-content/uploads/2020/07/logo_Politeknik-Elektronika-Negeri-Surabaya-thumb.png"
                        class="h-8 me-3" alt="Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Siap
                        Magang</span>
                </a>
                <ul
                    class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0 dark:text-gray-400">
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Tentang</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Privasi</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline me-4 md:me-6">Kontak</a>
                    </li>
                    <li>
                        <a href="#" class="hover:underline">Github</a>
                    </li>
                </ul>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <span class="block text-sm text-gray-500 sm:text-center dark:text-gray-400">
                © {{ date('Y') }} <a href="/" class="hover:underline">Surya Tegar Prasetya™</a>.
            </span>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("toggleSidebar");
            const sidebar = document.getElementById("sidebar");

            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("-translate-x-full");
            });
        });
    </script>

</body>

</html>
