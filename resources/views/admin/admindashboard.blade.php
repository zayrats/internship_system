@extends('admin.sidebaradmin')

@section('content')
    @if (session('success'))
        <div id="alert-success" class="bg-green-500 text-white p-3 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="bg-red-500 text-white p-3 rounded mb-4 text-center">
            {{ session('error') }}
        </div>
    @endif
    <div class="bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen Akun User</h1>

        <!-- Form Tambah User -->
        <button class="bg-blue-500 text-white px-4 py-2 rounded mb-4" onclick="openModal()">Tambah User</button>

        <!-- Filter Pencarian -->
        <form method="GET" class="mb-4">
            <input type="text" name="search" placeholder="Cari user..." class="border px-4 py-2 rounded"
                value="{{ request('search') }}">
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Cari</button>
        </form>

        <!-- Tabel User -->
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Role</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="border p-2">{{ $user->username }}</td>
                        <td class="border p-2">{{ $user->email }}</td>
                        <td class="border p-2">
                            <form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="role" class="border px-2 py-1 rounded">
                                    <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Mahasiswa" {{ $user->role == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa
                                    </option>
                                    <option value="Perusahaan" {{ $user->role == 'Perusahaan' ? 'selected' : '' }}>
                                        Perusahaan</option>

                                </select>


                        </td>
                        <td>
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div id="userModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded w-96">
            <h2 class="text-xl font-semibold mb-4">Tambah User</h2>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama" class="w-full border p-2 mb-2 rounded" required>
                <input type="email" name="email" placeholder="Email" class="w-full border p-2 mb-2 rounded" required>
                <input type="password" name="password" placeholder="Password" class="w-full border p-2 mb-2 rounded"
                    required>
                <select name="role" class="w-full border p-2 mb-2 rounded">
                    <option value="admin">Admin</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="perusahaan">Perusahaan</option>
                </select>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2"
                        onclick="closeModal()">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
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
@endsection
