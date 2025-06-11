@extends('master')
@section('content')
    <div class="min-h-screen p-3  bg-gray-100 dark:bg-gray-700">

        <h3 class="bg-gray-100 dark:bg-gray-700 text-3xl font-bold text-gray-900 dark:text-gray-100 text-center py-5">
            Cerita Mahasiswa
        </h3>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" id="experienceTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-600 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(0)">Nama Perusahaan</th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(1)">Mahasiswa</th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(2)">Pengalaman</th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(3)">Periode Magang</th>
                        <th class="px-6 py-3 cursor-pointer" onclick="sortTable(4)">Rating</th>
                        <th class="px-6 py-3"><span class="sr-only">Detail</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 dark:border-gray-700">
                    @foreach ($data as $item)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $item->company_name }}
                            </td>
                            <td class="px-6 py-4">{{ $item->student_name }}</td>
                            <td class="px-6 py-4">{{ $item->feedback }}</td>
                            <td class="px-6 py-4">{{ $item->start_date }} - {{ $item->end_date }}</td>

                            {{-- Warna rating --}}
                            <td class="px-6 py-4 font-bold text-yellow-500">{{ $item->rating }}</td>

                            {{-- Tombol Detail --}}
                            <td class="px-6 py-4 text-center">
                                <button class="text-blue-600 hover:underline open-modal"
                                    data-company_name="{{ $item->company_name }}" data-feedback="{{ $item->feedback }}"
                                    data-company_logo="{{ asset($item->company_logo) }}"
                                    data-student_name="{{ $item->student_name }}" data-position="{{ $item->position }}"
                                    data-start_date="{{ $item->start_date }}" data-end_date="{{ $item->end_date }}"
                                    data-duration="{{ \Carbon\Carbon::parse($item->start_date)->diffInMonths(\Carbon\Carbon::parse($item->end_date)) }} bulan"
                                    data-company_address="{{ $item->company_address }}"
                                    data-kp_book="{{ $item->kp_book }}">
                                    Selengkapnya
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- Modal Detail -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">

                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detail Internship</h2>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        ✖
                    </button>
                </div>

                <!-- Logo & Perusahaan -->
                <div class="flex items-center space-x-4 mb-4">
                    <img id="modalCompanyLogo" class="w-20 h-20 object-cover rounded-lg shadow-md" alt="Company Logo">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white" id="modalCompanyName"></p>
                        <dd></dd>
                        <p class="text-sm text-gray-600 dark:text-gray-400" id="modalCompanyAddress"></p>
                    </div>
                </div>

                <!-- Detail Informasi -->
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <p class="font-medium">Nama Mahasiswa:</p>
                        <p id="modalStudentName" class="text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <p class="font-medium">Posisi:</p>
                        <p id="modalPosition" class="text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <p class="font-medium">Durasi Magang:</p>
                        <p id="modalDuration" class="text-gray-900 dark:text-white"></p>
                    </div>
                    <div>
                        <p class="font-medium">Feedback:</p>
                        <p id="modalFeedback" class="text-gray-900 dark:text-white"></p>
                    </div>
                </div>

                <!-- Buku KP -->
                <div class="mt-6 flex items-center justify-between bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                    <p class="text-gray-900 dark:text-white font-medium">Buku KP:</p>
                    <button id="modalKpBook"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:underline"
                        onclick="openKpBookModal('{{ $item->kp_book }}')">
                        Lihat Buku KP
                    </button>
                </div>

                <!-- Tombol Tutup -->
                {{-- <div class="mt-6 flex justify-end">
                <button id="closeModalBottom"
                    class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-semibold">
                    Tutup
                </button>
            </div> --}}
            </div>
        </div>
        <!-- Modal Lihat Buku KP -->
        <div id="kpBookModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center mt-24">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
                <h2 class="text-xl font-bold text-center mb-4 text-black">Buku KP</h2>

                <iframe id="kpBookFrame" src="#toolbar=0" class="w-full h-[500px]"></iframe>

                <div class="flex justify-center mt-6">
                    <button id="closeKpBookModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("detailModal");
            const closeModal = document.getElementById("closeModal");
            const closeModalBottom = document.getElementById("closeModalBottom");
            const openButtons = document.querySelectorAll(".open-modal");

            openButtons.forEach(button => {
                button.addEventListener("click", function() {

                    document.getElementById("modalCompanyName").innerText = button.getAttribute(
                        "data-company_name"
                    );
                    document.getElementById("modalFeedback").innerText = button.getAttribute(
                        "data-feedback");
                    document.getElementById("modalStudentName").innerText = button.getAttribute(
                        "data-student_name");
                    document.getElementById("modalPosition").innerText = button.getAttribute(
                        "data-position");
                    document.getElementById("modalDuration").innerText = button.getAttribute(
                        "data-duration");
                    document.getElementById("modalCompanyAddress").innerText = button.getAttribute(
                        "data-company_address");

                    let companyLogo = button.getAttribute("data-company_logo");
                    document.getElementById("modalCompanyLogo").setAttribute("src", companyLogo);

                    let kpBookLink = button.getAttribute("data-kp_book");
                    document.getElementById("modalKpBook").setAttribute("href", kpBookLink);

                    modal.classList.remove("hidden");
                });
            });

            closeModal.addEventListener("click", function() {
                modal.classList.add("hidden");
            });

            closeModalBottom.addEventListener("click", function() {
                modal.classList.add("hidden");
            });

            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kpBookModal = document.getElementById("kpBookModal");
            const kpBookFrame = document.getElementById("kpBookFrame");
            const closeKpBookModalBtn = document.getElementById("closeKpBookModal");

            // Fungsi untuk menampilkan modal dan menampilkan PDF
            window.openKpBookModal = function(kpBookUrl) {
                kpBookFrame.src = kpBookUrl + '#toolbar=0'; // Load Buku KP di iframe
                kpBookModal.classList.remove("hidden"); // Tampilkan modal
            };

            // Tutup modal saat tombol "Tutup" ditekan
            closeKpBookModalBtn.addEventListener("click", function() {
                kpBookModal.classList.add("hidden");
                kpBookFrame.src = ""; // Kosongkan iframe saat modal ditutup
            });
        });
    </script>
    <script>
        // DataTables Init
        $(document).ready(function() {
            $('#experienceTable').DataTable({
                responsive: true,
                ordering: true,
                paging: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    },
                    zeroRecords: "Tidak ditemukan data yang cocok"
                }
            });
        });
    </script>
@endsection
