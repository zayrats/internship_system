@extends('pustakawan.sidebarpustakawan')

@section('content')
    <div class="p-6 overflow-x-auto">
        <h2 class="text-3xl font-semibold mb-6 text-gray-800 dark:text-white border-b pb-3">📘 Daftar Pengumpulan Buku KP
        </h2>
        <table id="kpTable"
            class="min-w-full divide-y divide-gray-200 bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden text-sm">
            <thead class="bg-blue-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm uppercase tracking-wide">
                <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-200">
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
                                class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md shadow transition">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <!-- Modal Detail/Edit -->
    <div id="editModal"
        class="fixed inset-0 hidden z-50 items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm transition-all duration-300 scale-95 opacity-0">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 w-full max-w-2xl">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">📝 Detail Pengajuan KP</h3>
            @if (isset($item))
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
                        <button type="button" class="bg-yellow-500 text-white px-3 py-1 rounded"
                            onclick="openKpBookModal()">Lihat Buku KP</button>
                        <button type="button" class="bg-yellow-500 text-white px-3 py-1 rounded"
                            onclick="openDraftKpBookModal()">Lihat Draft Buku KP</button>
                    </div>

                    <div class="mb-3">
                        <label for="book_status" class="block text-sm font-medium">Status Buku KP</label>
                        <select name="book_status" id="bookStatus" required class="w-full border rounded px-3 py-2">
                            <option value="Pending">Menunggu</option>
                            <option value="Approved">Terima</option>
                            <option value="Rejected">Tolak</option>
                        </select>
                    </div>

                    <div id="messageField" class="mb-3">
                        <label for="message" class="block text-sm font-medium">Revisi</label>
                        <textarea name="message" id="message" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-500">Batal</button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 shadow">
                            ✅ Simpan
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <!-- Modal Lihat Buku KP -->
    <div id="kpBookModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <h2 class="text-lg font-semibold mb-4 text-center">Buku KP</h2>
            <iframe id="kpBookFrame" class="w-full h-[500px]"></iframe>
            <div class="flex justify-center mt-6">
                <button id="closeKpBookModal"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Draft Buku KP -->
    <div id="draftKpBookModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
            <h2 class="text-lg font-semibold mb-4 text-center">Draft Buku KP</h2>
            <iframe id="draftKpBookFrame" class="w-full h-[500px]"></iframe>
            <div class="flex justify-center mt-6">
                <button id="closeDraftKpBookModal"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">Tutup</button>
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
            document.getElementById('editForm').action = '/pustakawan/update/' + internship.id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openKpBookModal() {
            document.getElementById('kpBookModal').classList.remove('hidden');
        }

        function openDraftKpBookModal() {
            document.getElementById('draftKpBookModal').classList.remove('hidden');
        }

        document.getElementById("closeKpBookModal").addEventListener("click", function() {
            document.getElementById("kpBookModal").classList.add("hidden");
            document.getElementById("kpBookFrame").src = "";
        });

        document.getElementById("closeDraftKpBookModal").addEventListener("click", function() {
            document.getElementById("draftKpBookModal").classList.add("hidden");
            document.getElementById("draftKpBookFrame").src = "";
        });
        $(document).ready(function() {
            $('#kpTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 20, 50],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "→",
                        previous: "←"
                    },
                    zeroRecords: "Tidak ada data yang cocok",
                }
            });
        });
        modal.classList.remove("hidden");
        modal.classList.add("scale-100", "opacity-100");
    </script>
@endsection
