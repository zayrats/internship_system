@extends('admin.sidebaradmin')

@section('content')
    @if (session('success'))
        <div id="alert-success" class="bg-green-500 text-white p-3 rounded mb-4 text-center shadow">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="bg-red-500 text-white p-3 rounded mb-4 text-center shadow">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold">👥 Manajemen Akun User</h1>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition"
                onclick="openModal()">
                ➕ Tambah User
            </button>
        </div>

        <!-- Tabel User -->
        <div class="overflow-x-auto rounded">
            <table id="userTable" class="min-w-full table-auto border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border px-4 py-2">Nama</th>
                        <th class="border px-4 py-2">Email</th>
                        <th class="border px-4 py-2">Role</th>
                        <th class="border px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $user->username }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2">
                                <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role"
                                        class="border px-2 py-1 rounded focus:outline-none focus:ring focus:ring-blue-400">
                                        <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="Mahasiswa" {{ $user->role == 'Mahasiswa' ? 'selected' : '' }}>
                                            Mahasiswa</option>
                                        <option value="Pustakawan" {{ $user->role == 'Pustakawan' ? 'selected' : '' }}> Pustakawan</option>
                                            {{-- <option value="Perusahaan" {{ $user->role == 'Perusahaan' ? 'selected' : '' }}>
                                            Perusahaan</option> --}}
                                    </select>
                            </td>
                            <td class="border px-4 py-2 text-center">
                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition shadow">
                                    💾 Simpan
                                </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div id="userModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center transition duration-300 ease-out">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
            <h2 class="text-xl font-semibold mb-4">➕ Tambah User</h2>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama"
                    class="w-full border p-2 mb-3 rounded focus:ring-2 focus:ring-blue-400" required>
                <input type="email" name="email" placeholder="Email"
                    class="w-full border p-2 mb-3 rounded focus:ring-2 focus:ring-blue-400" required>
                <input type="password" name="password" placeholder="Password"
                    class="w-full border p-2 mb-3 rounded focus:ring-2 focus:ring-blue-400" required>
                <select name="role" class="w-full border p-2 mb-4 rounded focus:ring-2 focus:ring-blue-400">
                    <option value="Admin">Admin</option>
                    <option value="Mahasiswa">Mahasiswa</option>
                    <option value="Pustakawan">Pustakawan</option>
                    {{-- <option value="perusahaan">Perusahaan</option> --}}
                </select>
                <div class="flex justify-end space-x-2">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded"
                        onclick="closeModal()">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Modal -->
    <script>
        function openModal() {
            document.getElementById('userModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }
    </script>

    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Inisialisasi DataTable -->
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Data kosong",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "→",
                        previous: "←"
                    }
                }
            });
        });
    </script>
@endsection
