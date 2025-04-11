<!DOCTYPE html>
<html lang="id">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin - Ayo Magang</title>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-full min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white shadow-lg transition-transform duration-300 ease-in-out transform md:translate-x-0"
            id="sidebar">
            <div class="p-4 flex items-center justify-between">
                <span class="text-xl font-bold">Admin Panel</span>
                <button id="toggleSidebar" class="md:hidden text-white focus:outline-none">
                    ☰
                </button>
            </div>
            <nav class="p-4">
                <ul>
                    <li>
                        <a href="{{ route('admin.statistics') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            📊 Statistik
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.monitoring') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            👨‍🎓 Monitoring Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jobs') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            📋 Manajemen Lowongan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            👥 Manajemen User
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
        document.addEventListener("DOMContentLoaded", function () {
            const toggleButton = document.getElementById("toggleSidebar");
            const sidebar = document.getElementById("sidebar");

            toggleButton.addEventListener("click", function () {
                sidebar.classList.toggle("-translate-x-full");
            });
        });
    </script>

</body>

</html>
