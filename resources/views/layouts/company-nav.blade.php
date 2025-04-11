<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-full min-h-screen">


        <aside
            class="w-64 bg-gray-800 text-white shadow-lg transition-transform duration-300 ease-in-out transform md:translate-x-0"
            id="sidebar">
            <div class="p-4 flex items-center justify-between">
                <span class="text-xl font-bold">Dashboard Perusahaan</span>
                <button id="toggleSidebar" class="md:hidden text-white focus:outline-none">
                    ☰
                </button>
            </div>
            <nav class="p-4">
                <ul>
                    <li>
                        <a href="{{ route('companydashboard') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            🏢 Profil Perusahaan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vacancy.index') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            📌 Buka Lowongan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('applications.index') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            📋 Seleksi Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vacancymonitor') }}"
                            class="block py-2 px-3 rounded hover:bg-blue-600 transition-all duration-200">
                            🎓 Pantau Mahasiswa
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
</body>

</html>
