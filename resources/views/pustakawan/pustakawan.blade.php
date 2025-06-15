@extends('pustakawan.sidebarpustakawan')

@section('content')
    <!-- Template untuk semua halaman: pustakawan.blade.php, before.blade.php, after.blade.php -->
    <!-- Gunakan Tailwind CSS dan pastikan ini berada di dalam layout blade yang sesuai -->

    <!-- Daftar Data -->
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Daftar Pengajuan KP</h2>
        <table class="min-w-full bg-white border border-gray-200 rounded-xl overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Perusahaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Divisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durasi (bulan)</th>
                    <th class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $item->student_name }}</td>
                        <td class="px-6 py-4">{{ $item->company_name }}</td>
                        <td class="px-6 py-4">{{ $item->position }}</td>
                        <td class="px-6 py-4">{{ $item->duration }}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openEditModal({{ $item->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Detail</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Edit -->
    <div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 flex">
        <div class="bg-white rounded-lg p-6 w-full max-w-xl">
            <h3 class="text-xl font-semibold mb-4">Detail Pengajuan KP</h3>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="internship_id" id="internshipId">

                <div class="mb-3">
                    <label class="block font-medium text-sm">Nama Mahasiswa</label>
                    <p id="studentName" class="text-gray-700"></p>
                </div>
                <div class="mb-3">
                    <label class="block font-medium text-sm">Perusahaan</label>
                    <p id="companyName" class="text-gray-700"></p>
                </div>
                <div class="mb-3">
                    <label class="block font-medium text-sm">Divisi</label>
                    <p id="position" class="text-gray-700"></p>
                </div>
                <div class="mb-3">
                    <label class="block font-medium text-sm">Durasi</label>
                    <p id="duration" class="text-gray-700"></p>
                </div>
                <div class="flex justify-end gap-2 mb-3">
                    <button type="button" class="text-white bg-yellow-500 hover:underline rounded px-3 py-1"
                        onclick="openKpBookModal()">
                        Lihat Buku KP
                    </button>
                    <button type="button" class="text-white bg-yellow-500 hover:underline rounded px-3 py-1"
                        onclick="openDraftKpBookModal()">
                        Lihat Draft Buku KP
                    </button>
                </div>


                <div class="mb-3">
                    <label for="book_status" class="block text-sm font-medium">Status Buku KP</label>
                    <select name="book_status" id="bookStatus" required onchange="toggleMessageField()"
                        class="w-full border rounded px-3 py-2">
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <div id="messageField" class="mb-3">
                    <label for="message" class="block text-sm font-medium">Pesan</label>
                    <textarea name="message" id="message" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-500">Batal</button>
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Lihat Buku KP -->
    <div id="kpBookModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center ">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center">Buku KP</h2>

            <iframe id="kpBookFrame" class="w-full h-[500px]"></iframe>

            <div class="flex justify-center mt-6">
                <button id="closeKpBookModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <!-- Modal Draft KP Book -->
    <div id="draftKpBookModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center ">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center">Draft Buku KP</h2>

            <iframe id="draftKpBookFrame" class="w-full h-[500px]"></iframe>

            <div class="flex justify-center mt-6">
                <button id="closeDraftKpBookModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <script>
        const data = @json($data);

        function openEditModal(id) {
            const internship = data.find(d => d.id == id);

            document.getElementById('internshipId').value = internship.id;
            document.getElementById('studentName').innerText = internship.student_name;
            document.getElementById('companyName').innerText = internship.company_name;
            document.getElementById('position').innerText = internship.position;
            document.getElementById('duration').innerText = internship.duration + ' bulan';
            document.getElementById('kpBookFrame').src = internship.kp_book;
            document.getElementById('draftKpBookFrame').src = internship.draft_kp_book;
            document.getElementById('bookStatus').value = internship.book_status || 'Approved';
            document.getElementById('message').value = internship.message || '';
            toggleMessageField();

            document.getElementById('editForm').action = '/pustakawan/update/' + internship.id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function toggleMessageField() {
            const status = document.getElementById('bookStatus').value;
            document.getElementById('messageField').classList.toggle('hidden', status !== 'Rejected');
        }
    </script>
@endsection
