<!DOCTYPE html>
<html lang="id">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Ayo Magang</title>
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
        <!-- Sidebar -->
        <!-- Sidebar -->
        <aside
            class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-md min-h-screen">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                <span class="text-lg font-semibold text-gray-800 dark:text-white">📘 Pustakawan</span>
                <button id="toggleSidebar" class="md:hidden text-gray-600 dark:text-gray-300">
                    ☰
                </button>
            </div>

            <nav class="p-4 space-y-2">
                <a href="{{ route('pustakawan') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-blue-100 dark:text-gray-200 dark:hover:bg-blue-600 transition-all duration-200">
                    📚 <span class="ml-3">Semua Buku KP</span>
                </a>

                <a href="{{ route('pustakawan.before') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-yellow-800 bg-yellow-50 hover:bg-yellow-100 rounded-lg dark:text-yellow-300 dark:bg-yellow-900 dark:hover:bg-yellow-800 transition-all duration-200">
                    ⏳ <span class="ml-3">Belum Diverifikasi</span>
                </a>

                <a href="{{ route('pustakawan.after') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-green-800 bg-green-50 hover:bg-green-100 rounded-lg dark:text-green-300 dark:bg-green-900 dark:hover:bg-green-800 transition-all duration-200">
                    ✅ <span class="ml-3">Sudah Diverifikasi</span>
                </a>

                <hr class="my-3 border-t dark:border-gray-600">

                <a href="{{ route('logout') }}"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-all duration-200">
                    🚪 <span class="ml-3">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6  bg-gray-500">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButton = document.getElementById("toggleSidebar");
            const sidebar = document.getElementById("sidebar");

            toggleButton.addEventListener("click", function() {
                sidebar.classList.toggle("-translate-x-full");
            });
        });
    </script>
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 ">
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
</body>

</html>
