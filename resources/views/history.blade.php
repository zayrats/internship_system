@extends('master')
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
    <div class="min-h-screen p-3  bg-gray-100 dark:bg-gray-700">



        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center my-5">Riwayat Pengajuan Magang</h3>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-600 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Perusahaan</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Tanggal Pengajuan</th>
                        <th scope="col" class="px-6 py-3">Pekerjaan</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item->company_name }}
                            </th>
                            <td class="px-6 py-4 items-start text-start">
                                <div
                                    class="mx-auto font-medium text-sm
                                    {{ strtolower($item->status) === 'Pending' ? 'text-yellow-500 dark:text-yellow-600' : '' }}
                                    {{ strtolower($item->status) === 'Approved' ? 'text-green-500 dark:text-green-600' : '' }}
                                    {{ strtolower($item->status) === 'Rejected' ? 'text-red-500 dark:text-red-600' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </div>
                            </td>

                            <td class="px-6 py-4">{{ $item->application_date }}</td>
                            <td class="px-6 py-4">{{ $item->division }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="#"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline detail-btn"
                                    data-id="{{ $item->company_id }}" data-name="{{ $item->company_name }}"
                                    data-address="{{ $item->company_address }}"
                                    data-logo="{{ asset('storage/' . $item->company_logo) }}"
                                    data-requirements="{{ $item->requirements }}" data-division="{{ $item->division }}"
                                    data-duration="{{ $item->duration }}" data-type="{{ $item->type }}"
                                    data-status="{{ $item->status }}"
                                    data-application_date="{{ $item->application_date }}">
                                    Lihat
                                </a>
                                <a href="{{ route('deleteHistory', $item->id) }}" class="text-red-600 hover:underline">
                                    Hapus
                                </a>

                                @if (strtolower(trim($item->status)) === 'finished')
                                    @if ($item->feedback)
                                        <button class="text-green-600 hover:underline"
                                            onclick="openFeedbackModal('{{ $item->company_name }}', '{{ $item->title }}', '{{ $item->start_date }}', '{{ $item->end_date }}', '{{ $item->position }}', '{{ $item->feedback }}', '{{ $item->rating }}')">
                                            Lihat Rating
                                        </button>
                                    @else
                                        <button class="text-green-600 hover:underline" onclick="openFeedbackModal()">
                                            Isi Pengalaman
                                        </button>
                                    @endif

                                    @if ($item->kp_book)
                                        <button class="text-yellow-500 hover:underline"
                                            onclick="openKpBookModal('{{ $item->kp_book }}')">
                                            Lihat Buku KP
                                        </button>
                                    @else
                                        <button class="text-yellow-500 hover:underline"
                                            onclick="openUploadKpModal('{{ $item->internship_id }}', '{{ $item->id }}')">
                                            Unggah Buku KP
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Modal Detail -->
            <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <h2 class="text-lg text-center font-bold text-center mb-4 text-gray-900 dark:text-white ">Detail Perusahaan</h2>

                    <!-- Logo Perusahaan di Tengah -->
                    <div class="flex justify-center mb-4">
                        <img id="modalLogo" class="w-32 h-32 object-cover rounded-full" alt="Logo Perusahaan">
                    </div>

                    <hr class="my-4 border-gray-300">

                    <!-- Informasi Perusahaan -->
                    <div class="grid grid-cols-2 gap-4 font-semibold mt-4  text-gray-700 dark:text-gray-300">
                        <p class="font-semibold text-gray-900 dark:text-white">Nama Perusahaan:</p>
                        <p class="text-gray-900 dark:text-white" id="modalName"></p>

                        <p class="font-semibold text-gray-900 dark:text-white">Alamat:</p>
                        <p class="text-gray-900 dark:text-white" id="modalAddress"></p>

                        <p class="font-semibold text-gray-900 dark:text-white">Persyaratan:</p>
                        <p class="text-gray-900 dark:text-white" id="modalRequirements"></p>
                    </div>



                    <!-- Informasi Tambahan -->
                    <div class="grid grid-cols-2 text-lg gap-4  text-gray-700 dark:text-gray-300">
                        <p class="font-semibold text-gray-900 dark:text-white">Divisi:</p>
                        <p id="modalDivision" class="text-gray-900 dark:text-white"></p>

                        <p class="font-semibold text-gray-900 dark:text-white">Durasi:</p>
                        <p id="modalDuration" class="text-gray-900 dark:text-white"></p>

                        <p class="font-semibold text-gray-900 dark:text-white">Tipe:</p>
                        <p id="modalType" class="text-gray-900 dark:text-white"></p>

                        <p class="font-semibold text-gray-900 dark:text-white">Status:</p>
                        <p id="modalStatus" class="text-gray-900 dark:text-white"></p>

                        <p class="font-semibold text-gray-900 dark:text-white">Tanggal Pengajuan:</p>
                        <p id="modalApplicationDate" class="text-gray-900 dark:text-white"></p>
                    </div>

                    <!-- Tombol Tutup -->
                    <div class="flex justify-center mt-6">
                        <button id="closeModal" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Feedback -->
            <div id="feedbackModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 w-96">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center" id="feedbackModalTitle">Feedback Magang</h2>

                    <form method="POST" action="{{ route('submitHistoryFeedback') }}" id="feedbackForm">
                        @csrf
                        <input type="hidden" name="internship_id" id="internshipId">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Perusahaan</label>
                        <input type="text" id="companyName" class="w-full p-2 border rounded bg-gray-100" readonly>

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Judul Buku KP</label>
                        <input type="text" name="title" id="title" class="w-full p-2 border rounded">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="startDate" class="w-full p-2 border rounded">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="endDate" class="w-full p-2 border rounded">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Posisi Magang</label>
                        <input type="text" name="position" id="position" class="w-full p-2 border rounded">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Rating</label>
                        <div id="ratingContainer" class="flex space-x-1 text-yellow-500 text-2xl cursor-pointer">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" name="rating" id="ratingValue" value="0">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Feedback</label>
                        <textarea name="feedback" id="feedback" class="w-full p-2 border rounded"></textarea>

                        <div class="flex justify-center mt-6">
                            <button type="button" id="closeModalFeedback"
                                class="bg-red-700 text-white p-2 rounded-lg mr-2">Tutup</button>
                            <button type="submit" id="submitFeedbackBtn"
                                class="bg-blue-500 text-white p-2 rounded">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Modal Unggah Buku KP -->
            <div id="uploadKpModal"
                class="hidden flex fixed inset-0 bg-gray-600 bg-opacity-50  items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center">Unggah Buku KP</h2>

                    <form id="uploadKpForm" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="applicationId">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Pilih File (PDF):</label>
                        <input type="file" name="kp_book" accept="application/pdf"
                            class="w-full p-2 border rounded mb-4" required>

                        {{-- <div class="flex justify-end gap-2"> --}}
                        <button type="button" id="closeUploadKpModal"
                            class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Unggah
                        </button>
                        {{-- </div> --}}
                    </form>
                </div>
            </div>
            <!-- Modal Lihat Buku KP -->
            <div id="kpBookModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center">Buku KP</h2>

                    <iframe id="kpBookFrame" class="w-full h-[500px]"></iframe>

                    <div class="flex justify-center mt-6">
                        <button id="closeKpBookModal"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailButtons = document.querySelectorAll('.detail-btn');
            const modal = document.getElementById('detailModal');
            const closeModal = document.getElementById('closeModal');

            // Elemen di dalam modal
            const modalName = document.getElementById('modalName');
            const modalAddress = document.getElementById('modalAddress');
            const modalRequirements = document.getElementById('modalRequirements');
            const modalLogo = document.getElementById('modalLogo');
            const modalDivision = document.getElementById('modalDivision');
            const modalDuration = document.getElementById('modalDuration');
            const modalType = document.getElementById('modalType');
            const modalStatus = document.getElementById('modalStatus');
            const modalApplicationDate = document.getElementById('modalApplicationDate');

            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    modalName.textContent = this.dataset.name;
                    modalAddress.textContent = this.dataset.address;
                    modalRequirements.textContent = this.dataset.requirements;
                    modalLogo.src = this.dataset.logo;
                    modalDivision.textContent = this.dataset.division;
                    modalDuration.textContent = this.dataset.duration;
                    modalType.textContent = this.dataset.type;
                    modalStatus.textContent = this.dataset.status;
                    modalApplicationDate.textContent = this.dataset.application_date;

                    modal.classList.remove('hidden');
                });
            });

            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("feedbackModal");
            const closeModalFeedback = document.getElementById("closeModalFeedback");
            const internshipIdInput = document.getElementById("internshipId");

            // Saat tombol "Isi Pengalaman" diklik, modal terbuka
            document.querySelectorAll(".open-modal").forEach(button => {
                button.addEventListener("click", function() {
                    const internshipId = this.getAttribute("data-id");
                    internshipIdInput.value = internshipId;
                    modal.classList.remove("hidden");
                });
            });

            // Tutup modal saat tombol "Batal" diklik
            closeModalFeedback.addEventListener("click", function() {
                modal.classList.add("hidden");
            });

            // Tutup modal jika klik di luar modal
            window.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.classList.add("hidden");
                }
            });
        });

        // Ambil elemen modal dan tombol close
        const uploadKpModal = document.getElementById("uploadKpModal");
        const closeUploadModalBtn = document.getElementById("closeUploadKpModal");

        // Pastikan fungsi ini global agar bisa dipanggil dari `onclick`
        function openUploadKpModal(internshipId, applicationId) {
            document.getElementById("applicationId").value = applicationId;

            let routeUrl = "{{ route('uploadKpBook', ['id' => ':id']) }}".replace(':id', applicationId);
            document.getElementById("uploadKpForm").action = routeUrl;

            uploadKpModal.classList.remove("hidden");
        }

        // Tutup modal saat tombol "Batal" ditekan
        closeUploadModalBtn.addEventListener("click", function() {
            uploadKpModal.classList.add("hidden");
        });

        // Tutup modal saat klik di luar modal
        window.addEventListener("click", function(event) {
            if (event.target === uploadKpModal) {
                uploadKpModal.classList.add("hidden");
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const kpBookModal = document.getElementById("kpBookModal");
            const kpBookFrame = document.getElementById("kpBookFrame");
            const closeKpBookModalBtn = document.getElementById("closeKpBookModal");

            // Fungsi untuk menampilkan modal dan menampilkan PDF
            window.openKpBookModal = function(kpBookUrl) {
                kpBookFrame.src = kpBookUrl; // Load Buku KP di iframe
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
        document.addEventListener("DOMContentLoaded", function() {
            const feedbackModal = document.getElementById("feedbackModal");
            const closeModalBtn = document.getElementById("closeModal");
            const feedbackForm = document.getElementById("feedbackForm");
            const submitFeedbackBtn = document.getElementById("submitFeedbackBtn");
            const stars = document.querySelectorAll("#ratingContainer .star");
            const ratingInput = document.getElementById("ratingValue");

            // Fungsi membuka modal (Mode Isi Pengalaman atau Lihat Rating)
            window.openFeedbackModal = function(companyName = '', title = '', startDate = '', endDate = '',
                position = '', feedback = '', rating = 0) {
                document.getElementById("companyName").value = companyName;
                document.getElementById("title").value = title;
                document.getElementById("startDate").value = startDate;
                document.getElementById("endDate").value = endDate;
                document.getElementById("position").value = position;
                document.getElementById("feedback").value = feedback;
                setRating(rating);

                // Jika feedback sudah ada, ubah modal menjadi mode "Lihat Rating"
                if (feedback || rating > 0) {
                    document.getElementById("feedbackModalTitle").textContent = "Lihat Rating";
                    submitFeedbackBtn.classList.add("hidden"); // Sembunyikan tombol Kirim
                    feedbackForm.querySelectorAll("input, textarea").forEach(el => el.setAttribute("readonly",
                        true));

                    // Nonaktifkan rating interaction
                    stars.forEach(star => star.style.pointerEvents = "none");
                } else {
                    document.getElementById("feedbackModalTitle").textContent = "Isi Pengalaman";
                    submitFeedbackBtn.classList.remove("hidden"); // Tampilkan tombol Kirim
                    feedbackForm.querySelectorAll("input, textarea").forEach(el => el.removeAttribute(
                        "readonly"));

                    // Aktifkan rating interaction
                    stars.forEach(star => star.style.pointerEvents = "auto");
                }

                feedbackModal.classList.remove("hidden");
            };

            // Fungsi untuk mengatur rating (highlight bintang)
            function setRating(value) {
                ratingInput.value = value;
                stars.forEach(star => {
                    star.style.color = star.dataset.value <= value ? "#FFD700" :
                    "#D1D5DB"; // Warna emas jika aktif, abu-abu jika tidak
                });
            }

            // Event listener untuk klik pada bintang rating
            stars.forEach(star => {
                star.addEventListener("click", function() {
                    setRating(this.dataset.value);
                });
            });

            // Tutup modal saat tombol "Tutup" ditekan
            closeModalBtn.addEventListener("click", function() {
                feedbackModal.classList.add("hidden");
            });
        });
    </script>
@endsection
