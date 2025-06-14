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
        <aside
            class="w-64 bg-gray-800 text-white shadow-lg transition-transform duration-300 ease-in-out transform md:translate-x-0"
            id="sidebar">
            <div class="p-4 flex items-center justify-between">
                <span class="text-xl font-bold">Pustakawan Panel</span>
                <button id="toggleSidebar" class="md:hidden text-white focus:outline-none">
                    ☰
                </button>
            </div>
            <nav class="p-4">
                <ul>
                    <li>
                        <a href="{{ route('pustakawan') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            📚 Semua Buku KP
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pustakawan.before') }}"
                            class="block py-2 px-3 rounded hover:bg-yellow-600 transition-all duration-200">
                            ⏳ Pending Review
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pustakawan.after') }}"
                            class="block py-2 px-3 rounded hover:bg-red-600 transition-all duration-200">
                            ❌ Ditolak
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            class="block py-2 px-3 rounded bg-red-500 text-white hover:bg-red-600 transition-all duration-200">
                            🚪 Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>


        <!-- Main Content -->
        <main class="flex-1 p-6">
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
