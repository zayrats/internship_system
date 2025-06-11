<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Perusahaan</title>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>

<body class="bg-gray-100">

    <div class="flex h-full min-h-screen">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-gray-900 text-white shadow-md md:translate-x-0 transform transition-transform duration-300 ease-in-out"
            id="sidebar">
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <span class="text-lg font-bold" value="{{ $company->name ?? '' }}">🏢 {{ $company->name ?? '' }}</span>
                <button id="toggleSidebar" class="md:hidden focus:outline-none text-white">
                    ☰
                </button>
            </div>
            <nav class="p-4 space-y-1 text-sm font-medium">
                @php
                    $active = request()->route()->getName();
                @endphp
                <a href="{{ route('companydashboard') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 {{ $active == 'companydashboard' ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                    <span>🏢</span> Profil Perusahaan
                </a>
                <a href="{{ route('vacancy.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 {{ $active == 'vacancy.index' ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                    <span>📌</span> Buka Lowongan
                </a>
                <a href="{{ route('applications.index') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 {{ $active == 'applications.index' ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                    <span>📋</span> Seleksi Mahasiswa
                </a>
                <a href="{{ route('vacancymonitor') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 {{ $active == 'vacancymonitor' ? 'bg-blue-600 text-white' : 'text-gray-300' }}">
                    <span>🎓</span> Pantau Mahasiswa
                </a>
                <a href="{{ route('logout') }}"
                    class="flex items-center gap-2 px-3 py-2 mt-4 rounded-lg bg-red-500 hover:bg-red-600 transition">
                    <span>🚪</span> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-gray-100">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-screen-xl mx-auto p-4 md:py-6 text-center text-gray-600 text-sm">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                <div class="flex items-center justify-center mb-2 sm:mb-0">
                    <img src="https://kompaspedia.kompas.id/wp-content/uploads/2020/07/logo_Politeknik-Elektronika-Negeri-Surabaya-thumb.png"
                        class="h-8 me-3" alt="Logo" />
                    <span class="text-xl font-semibold">Siap Magang</span>
                </div>
                <div class="flex flex-wrap justify-center gap-4 text-gray-500">
                    <a href="#" class="hover:underline">Tentang</a>
                    <a href="#" class="hover:underline">Privasi</a>
                    <a href="#" class="hover:underline">Kontak</a>
                    <a href="#" class="hover:underline">GitHub</a>
                </div>
            </div>
            <hr class="my-4 border-gray-200" />
            <span>© {{ date('Y') }} <a href="/" class="hover:underline">Surya Tegar Prasetya™</a>. All rights
                reserved.</span>
        </div>
    </footer>

    <!-- Script -->
    <script>
        const toggleButton = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');

        toggleButton?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>

</html>
