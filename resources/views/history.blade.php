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



        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center my-5">Riwayat Pengajuan KP</h3>
        <!-- Tombol trigger (optional placement) -->
        <button onclick="openAddApplicationModal()"
            class="text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg px-4 py-2 mb-4">
            Tambah Pengajuan KP
        </button>
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
                    @forelse ($data as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item->company_name }}
                            </th>
                            <td class="px-6 py-4 items-start text-start">
                                <div
                                    class="mx-auto font-medium text-sm
                        {{ strtolower($item->status) === 'pending' ? 'text-yellow-500 dark:text-yellow-600' : '' }}
                        {{ strtolower($item->status) === 'approved' ? 'text-green-500 dark:text-green-600' : '' }}
                        {{ strtolower($item->status) === 'rejected' ? 'text-red-500 dark:text-red-600' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $item->application_date }}</td>
                            <td class="px-6 py-4">{{ $item->division }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="#"
                                    class="rounded bg-white px-3 py-1 mx-1 font-medium text-blue-600 dark:text-blue-500 hover:underline detail-btn"
                                    data-id="{{ $item->company_id }}" data-name="{{ $item->company_name }}"
                                    data-address="{{ $item->company_address }}"
                                    data-logo="{{ asset($item->company_logo) }}"
                                    data-requirements="{{ $item->requirements }}" data-division="{{ $item->division }}"
                                    data-duration="{{ $item->duration }}" data-type="{{ $item->type }}"
                                    data-status="{{ $item->status }}"
                                    data-application_date="{{ $item->application_date }}">
                                    Lihat
                                </a>

                                <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                                    onclick="openEditApplicationModal('{{ $item->id }}',
                            '{{ $item->vacancy_id }}',
                            '{{ $item->status }}',
                            '{{ $item->application_date }}',
                            '{{ basename($item->document) }}')">
                                    Edit Pengajuan
                                </button>

                                <a href="{{ route('deleteHistory', $item->id) }}"
                                    class="text-white hover:underline rounded bg-red-500 px-3 py-1"
                                    onclick="return confirm('Yakin ingin menghapus pengajuan ini?')">
                                    Hapus
                                </a>

                                @if (strtolower(trim($item->status)) === 'finished')
                                    @if ($item->internship_id)
                                        @dump($item->book_status)
                                        {{-- jika status buku Rejected, maka tampil revisi --}}
                                        @if (strtolower(trim($item->book_status)) === 'rejected')
                                            <button class="text-white hover:underline bg-red-500 rounded px-3 py-1 mx-1"
                                                onclick="openFeedbackModal('{{ $item->internship_id_useless }}',
                                            '{{ $item->id }}',
                                            '{{ $item->company_id }}',
                                            '{{ $item->company_name }}',
                                            '{{ $item->title }}',
                                            '{{ $item->start_date }}',
                                            '{{ $item->end_date }}',
                                            '{{ $item->position }}',
                                            '{{ $item->feedback }}',
                                            '{{ $item->rating }}',
                                            '{{ $item->kp_book }}',
                                            '{{ $item->vacancy_id }}',
                                            '{{ $item->draft_kp_book }}',
                                            '{{ $item->book_status }}',
                                            '{{ $item->message }}')">
                                                Revisi
                                            </button>
                                        @else
                                            <button class="text-green-600 hover:underline bg-green-500 rounded px-3 py-1"
                                                onclick="openFeedbackModal('{{ $item->internship_id_useless }}',
                                            '{{ $item->id }}',
                                            '{{ $item->company_id }}',
                                            '{{ $item->company_name }}',
                                            '{{ $item->title }}',
                                            '{{ $item->start_date }}',
                                            '{{ $item->end_date }}',
                                            '{{ $item->position }}',
                                            '{{ $item->feedback }}',
                                            '{{ $item->rating }}',
                                            '{{ $item->kp_book }}',
                                            '{{ $item->vacancy_id }}',
                                            '{{ $item->draft_kp_book }}',
                                            '{{ $item->book_status }}',
                                            '{{ $item->message }}')">
                                                Lihat Rating
                                            </button>
                                            <input type="hidden" id="currentKpBookUrl"
                                                value="{{ asset($item->kp_book) }}">
                                            <input type="hidden" id="currentdraftKpBookUrl"
                                                value="{{ asset($item->draft_kp_book) }}">
                                        @endif
                                    @else
                                        <button class="text-white hover:underline bg-green-500 rounded px-3 py-1 mx-1"
                                            onclick="openFeedbackModal('{{ $item->internship_id_useless ?? '' }}',
                                    '{{ $item->id }}',
                                    '{{ $item->company_id }}',
                                    '{{ $item->company_name ?? '' }}',
                                    '{{ $item->title ?? '' }}',
                                    '{{ $item->start_date_vacancy }}',
                                    '{{ $item->end_date_vacancy }}',
                                    '{{ $item->division }}',
                                    '{{ $item->feedback ?? '' }}',
                                    '{{ $item->rating ?? '' }}',
                                    '{{ $item->kp_book ?? '' }}',
                                    '{{ $item->vacancy_id ?? '' }}')">
                                            Unggah Pengalaman
                                        </button>
                                    @endif

                                    @if (!$item->kp_book)
                                        {{-- <button class="text-white hover:underline bg-yellow-500 rounded px-3 py-1"
                                            onclick="openUploadKpModal('{{ $item->internship_id }}', '{{ $item->id }}')">
                                            Unggah Buku KP
                                        </button> --}}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Modal Detail -->
            <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <h2 class="text-lg text-center font-bold text-center mb-4 text-gray-900 dark:text-white ">Detail
                        Perusahaan</h2>

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
            <div id="feedbackModal"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center mt-24">
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 w-96">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center"
                        id="feedbackModalTitle">
                        Feedback Magang
                    </h2>

                    <form method="POST" action="#" enctype="multipart/form-data" id="feedbackForm">
                        @csrf
                        <input type="hidden" name="internship_id" id="internshipId">
                        <input type="hidden" name="application_id" id="applicationId">
                        <input type="hidden" name="company_id" id="modalCompanyId">
                        <input type="hidden" name="vacancy_id" class="hidden" id="modalVacancyId">
                        <input type="hidden" id="currentKpBookUrl">
                        <input type="hidden" id="currentdraftKpBookUrl">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Perusahaan</label>
                        <input type="text" disabled id="companyName"
                            class="w-full p-2 border rounded bg-gray-100 dark:text-black" readonly>

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Judul Buku KP</label>
                        <input type="text" name="title" id="title"
                            class="w-full p-2 border rounded dark:text-black">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="startDate"
                            class="w-full p-2 border rounded dark:text-black">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="endDate"
                            class="w-full p-2 border rounded dark:text-black">

                        <label class="block text-sm font-medium text-gray-900 dark:text-white">Posisi Magang</label>
                        <input type="text" name="position" id="position"
                            class="w-full p-2 border rounded dark:text-black">

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
                        <textarea name="feedback" id="feedback" class="w-full p-2 border rounded dark:text-black"></textarea>



                        {{-- if else for uploaded file --}}

                        <div id="kpBookReader" class="">
                            <label for="bookStatus" class="block text-sm font-medium text-gray-900 dark:text-white">Status
                                Buku KP</label>
                            {{-- sesuaikan nama status, jika Pending = Menunggu, Jika Approved = Disetujui , Jika Rejected = Ditolak --}}
                            <select disabled id="bookStatus"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                                <option value="Pending">Menunggu</option>
                                <option value="Approved">Disetujui</option>
                                <option value="Rejected">Ditolak</option>
                            </select>
                            <button type="button" class="text-white bg-yellow-500 hover:underline rounded px-3 py-1"
                                onclick="openKpBookModal()">
                                Lihat Buku KP
                            </button>
                            <button type="button" class="text-white bg-yellow-500 hover:underline rounded px-3 py-1"
                                onclick="openDraftKpBookModal()">
                                Lihat Draft Buku KP
                            </button>
                        </div>
                        <!-- Tambahkan Upload Buku KP -->
                        <div id="kpBookUpload" class="">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mt-4">Unggah Buku KP
                                (PDF)</label>
                            <input type="file" name="kp_book" accept="application/pdf"
                                class="w-full p-2 border rounded dark:text-black">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mt-4">Unggah Draft
                                (Judul & Abstract)</label>
                            <input type="file" name="draft_kp_book" accept="application/pdf"
                                class="w-full p-2 border rounded dark:text-black">
                        </div>
                        <div id='revision' class="">
                            <label for="bookStatus" class="block text-sm font-medium text-gray-900 dark:text-white">Status
                                Buku KP</label>
                            {{-- sesuaikan nama status, jika Pending = Menunggu, Jika Approved = Disetujui , Jika Rejected = Ditolak --}}
                            <select disabled id="bookStatus"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                                <option value="Pending">Menunggu</option>
                                <option value="Approved">Disetujui</option>
                                <option value="Rejected">Ditolak</option>
                            </select>

                            <label for="message" class="block text-sm font-medium text-gray-900 dark:text-white">Pesan
                                Buku
                                KP</label>
                            <textarea id="message" class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white" readonly></textarea>
                        </div>

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
            <div id="kpBookModal"
                class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center mt-24">
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

            <!-- Modal Draft KP Book -->
            <div id="draftKpBookModal"
                class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center mt-24">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center">Draft Buku KP</h2>

                    <iframe id="draftKpBookFrame" class="w-full h-[500px]"></iframe>

                    <div class="flex justify-center mt-6">
                        <button id="closeDraftKpBookModal"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Pengajuan KP -->
            <div id="applicationModal"
                class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center px-4">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl w-full max-w-lg p-6 space-y-6 relative">
                    <h3 class="text-2xl font-bold text-center text-gray-800 dark:text-white">Tambah Pengajuan KP</h3>

                    <form id="applicationForm" method="POST" action="{{ route('applications.store') }}"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <!-- Pilih Lowongan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">Pilih
                                Lowongan</label>
                            <select name="vacancy_id" id="vacancySelect" required
                                class="w-full p-2 border rounded bg-white dark:bg-gray-800 dark:text-white">
                                @foreach ($vacancies as $vacancy)
                                    <option value="{{ $vacancy->vacancy_id }}">{{ $vacancy->division }} -
                                        {{ $vacancy->company_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Upload Surat Pengantar -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-300">Surat Pengantar
                                (PDF)</label>
                            <input type="file" name="document" accept="application/pdf" required
                                class="w-full p-2 border rounded bg-white dark:bg-gray-800 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                        </div>

                        <!-- Tombol -->
                        <div class="space-y-2">
                            <button type="submit"
                                class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md transition duration-200">
                                Simpan Pengajuan
                            </button>
                            <button type="button" onclick="closeModal('applicationModal')"
                                class="w-full text-center text-red-500 hover:underline">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Pengajuan -->
            <div id="editApplicationModal"
                class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-xl w-full">
                    <h2 class="text-lg font-bold text-center mb-4 text-gray-900 dark:text-white">Edit Pengajuan</h2>
                    @if (isset($item))
                        <form id="editApplicationForm" method="POST" enctype="multipart/form-data"
                            action="{{ route('applications.update', ['id' => $item->id]) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" id="editApplicationId" name="application_id">

                            <!-- Lowongan (disabled) -->
                            <div class="mb-4">
                                <label class="block font-semibold text-gray-900 dark:text-white mb-1">Lowongan KP</label>
                                <select disabled name="vacancy_id" id="editVacancySelect"
                                    class="w-full border border-gray-300 rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                                    @foreach ($vacancies as $vacancy)
                                        <option value="{{ $vacancy->vacancy_id }}">
                                            {{ $vacancy->division }} - {{ $vacancy->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label class="block font-semibold text-gray-900 dark:text-white mb-1">Status</label>
                                {{-- @dump($data)($item->status) --}}
                                <select name="status" id="editStatusSelect" required
                                    class="w-full border border-gray-300 rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Finished">Finished</option>
                                </select>
                            </div>

                            <!-- Tanggal Pengajuan -->
                            <div class="mb-4">
                                <label class="block font-semibold text-gray-900 dark:text-white mb-1">Tanggal
                                    Pengajuan</label>
                                <input type="date" name="application_date" id="editApplicationDate"
                                    class="w-full border border-gray-300 rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                            </div>

                            <!-- Unggah Surat Lamaran -->
                            <div class="mb-4">
                                <label class="block font-semibold text-gray-900 dark:text-white mb-1">
                                    Unggah Surat Lamaran Baru
                                </label>

                                <!-- Tampilkan file lama -->
                                <p id="existingDocumentName" class="text-sm text-gray-600 dark:text-gray-300 mb-2"></p>

                                <!-- Input file baru -->
                                <input type="file" name="document" accept="application/pdf"
                                    class="w-full border border-gray-300 rounded-lg p-2 dark:bg-gray-700 dark:text-white">
                            </div>


                            <!-- Tombol -->
                            <div class="flex justify-end mt-6 gap-2">
                                <button type="button" onclick="closeEditApplicationModal()"
                                    class="bg-red-700 text-white px-4 py-2 rounded-lg">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <script>
        function openEditApplicationModal(applicationId, vacancyId, status, applicationDate, documentName) {
            const modal = document.getElementById('editApplicationModal');
            const form = document.getElementById('editApplicationForm');

            // Ubah form action ke endpoint yang sesuai
            form.action = "/applications/" + applicationId;

            // Isi field
            document.getElementById('editApplicationId').value = applicationId;
            document.getElementById('editVacancySelect').value = vacancyId;
            document.getElementById('editStatusSelect').value = status;
            document.getElementById('editApplicationDate').value = applicationDate;

            // Tampilkan nama file dokumen lama
            const docNameElement = document.getElementById('existingDocumentName');
            if (documentName && documentName !== 'null') {
                docNameElement.textContent = "File sebelumnya: " + documentName;
            } else {
                docNameElement.textContent = "Belum ada file surat lamaran.";
            }

            modal.classList.remove('hidden');
        }

        function closeEditApplicationModal() {
            document.getElementById('editApplicationModal').classList.add('hidden');
        }
    </script>



    <script>
        function openAddApplicationModal() {
            document.getElementById("applicationModal").classList.remove("hidden");
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add("hidden");
        }
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('applicationForm');

            form.addEventListener('submit', function(e) {
                const select = document.getElementById('vacancySelect');
                if (select) {
                    form.action = '/applications/add';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('applicationForm');
            const select = document.getElementById('vacancySelect');
            const fileInput = form.querySelector('input[name="document"]');

            form.addEventListener('submit', function(e) {
                let errors = [];

                // Validasi pilihan lowongan
                if (!select.value) {
                    errors.push("Silakan pilih lowongan terlebih dahulu.");
                }

                // Validasi file
                const file = fileInput.files[0];
                if (!file) {
                    errors.push("Silakan unggah surat pengantar (PDF).");
                } else {
                    const allowedTypes = ['application/pdf'];
                    if (!allowedTypes.includes(file.type)) {
                        errors.push("File harus berupa PDF.");
                    }

                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        errors.push("Ukuran file tidak boleh lebih dari 2MB.");
                    }
                }

                if (errors.length > 0) {
                    e.preventDefault(); // Hentikan submit
                    alert(errors.join('\n'));
                }
            });
        });
    </script>

    <script>
        //lihat detail
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
        //feedback upload
        // document.addEventListener("DOMContentLoaded", function() {
        //     const modal = document.getElementById("feedbackModal");
        //     const closeModalFeedback = document.getElementById("closeModalFeedback");
        //     const internshipIdInput = document.getElementById("internshipId");

        //     // Saat tombol "Isi Pengalaman" diklik, modal terbuka
        //     document.querySelectorAll(".open-modal").forEach(button => {
        //         button.addEventListener("click", function() {
        //             const internshipId = this.getAttribute("data-id");
        //             internshipIdInput.value = internshipId;
        //             modal.classList.remove("hidden");
        //         });
        //     });

        //     // Tutup modal saat tombol "Batal" diklik
        //     closeModalFeedback.addEventListener("click", function() {
        //         modal.classList.add("hidden");
        //     });

        //     // Tutup modal jika klik di luar modal
        //     window.addEventListener("click", function(event) {
        //         if (event.target === modal) {
        //             modal.classList.add("hidden");
        //         }
        //     });
        // });

        // // Ambil elemen modal dan tombol close
        // const uploadKpModal = document.getElementById("uploadKpModal");
        // const closeUploadModalBtn = document.getElementById("closeUploadKpModal");

        // // Pastikan fungsi ini global agar bisa dipanggil dari `onclick`
        // function openUploadKpModal(internshipId, applicationId) {
        //     document.getElementById("applicationId").value = applicationId;

        //     let routeUrl = "{{ route('uploadKpBook', ['id' => ':id']) }}".replace(':id', applicationId);
        //     document.getElementById("uploadKpForm").action = routeUrl;

        //     uploadKpModal.classList.remove("hidden");
        // }

        // // Tutup modal saat tombol "Batal" ditekan
        // closeUploadModalBtn.addEventListener("click", function() {
        //     uploadKpModal.classList.add("hidden");
        // });

        // // Tutup modal saat klik di luar modal
        // window.addEventListener("click", function(event) {
        //     if (event.target === uploadKpModal) {
        //         uploadKpModal.classList.add("hidden");
        //     }
        // });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // const kpBookModal = document.getElementById("kpBookModal");
            // // const kpBookFrame = document.getElementById("kpBookFrame");
            // const closeKpBookModalBtn = document.getElementById("closeKpBookModal");

            // Fungsi untuk menampilkan modal dan menampilkan PDF
            // window.openKpBookModal = function(kpBookUrl) {
            //     kpBookFrame.src = kpBookUrl; // Load Buku KP di iframe
            //     kpBookModal.classList.remove("hidden"); // Tampilkan modal
            // };

            // Tutup modal saat tombol "Tutup" ditekan
            // closeKpBookModalBtn.addEventListener("click", function() {
            //     kpBookModal.classList.add("hidden");
            //     kpBookFrame.src = ""; // Kosongkan iframe saat modal ditutup
            // });
        });
    </script>
    <script>
        // lihat dan isi feedback
        document.addEventListener("DOMContentLoaded", function() {
            const feedbackModal = document.getElementById("feedbackModal");
            const closeModalBtn = document.getElementById("closeModalFeedback");
            const closeModalPdf = document.getElementById("closeKpBookModal");
            const feedbackForm = document.getElementById("feedbackForm");
            const submitFeedbackBtn = document.getElementById("submitFeedbackBtn");
            const stars = document.querySelectorAll("#ratingContainer .star");
            const ratingInput = document.getElementById("ratingValue");

            // Fungsi membuka modal (Mode Isi Pengalaman atau Lihat Rating)
            window.openFeedbackModal = function(internshipId, applicationId, companyId, companyName = '', title =
                '', startDate =
                '', endDate = '', position = '', feedback = '', rating = 0, kpBookUrl = '', vacancyId = null,
                draftKpBookUrl = '', bookStatus = '', message = ''
            ) {
                console.log("🚀 ~ document.addEventListener ~ kpBook:", internshipId)
                console.log("🚀 ~ book_status:", bookStatus)
                if (!internshipId) {
                    feedbackForm.method = 'post'
                    feedbackForm.action = `/history/${applicationId}/submit-feedback`
                    document.getElementById("modalVacancyId").value = vacancyId;
                    console.log(`🚀 ~ document.addEventListener ~  document.getElementById("modalVacancyId"):`,
                        document.getElementById("modalVacancyId"))

                } else {
                    feedbackForm.method = 'get'
                }
                console.log(feedbackForm) // Set ID aplikasi dan perusahaan
                document.getElementById("internshipId").value = internshipId;
                document.getElementById("applicationId").value = applicationId;
                document.getElementById("modalCompanyId").value = companyId;

                // Isi data ke dalam form
                document.getElementById("companyName").value = companyName;
                document.getElementById("title").value = title;
                document.getElementById("startDate").value = startDate;
                document.getElementById("endDate").value = endDate;
                document.getElementById("position").value = position;
                document.getElementById("feedback").value = feedback || '';
                document.getElementById("bookStatus").value = bookStatus;
                document.getElementById("message").value = message || '';
                setRating(rating || 0);
                if (!internshipId) {
                    document.getElementById("kpBookUpload").classList.remove("hidden");
                    document.getElementById("kpBookReader").classList.add("hidden");
                    document.getElementById("revision").classList.add("hidden");
                } else {
                    if (bookStatus === 'Rejected') {
                        document.getElementById("kpBookUpload").classList.remove("hidden");
                        document.getElementById("kpBookReader").classList.add("hidden");
                        document.getElementById("revision").classList.remove("hidden");
                    } else {
                        //tampil buku kp
                        document.getElementById("kpBookUpload").classList.add("hidden");
                        document.getElementById("kpBookReader").classList.remove("hidden");
                        document.getElementById("revision").classList.add("hidden");
                        const kpBookModal = document.getElementById("kpBookModal");
                        const kpBookFrame = document.getElementById("kpBookFrame");
                        const closeKpBookModalBtn = document.getElementById("closeKpBookModal");
                        // const draftKpBookUrl = document.getElementById("draftKpBookUrl").value;
                        const draftKpBookModal = document.getElementById("draftKpBookModal");
                        const draftKpBookFrame = document.getElementById("draftKpBookFrame");
                        const closeDraftKpBookModalBtn = document.getElementById("closeDraftKpBookModal");
                        // set value untuk currentKpBookUrl
                        document.getElementById('currentKpBookUrl').value = kpBookUrl;

                        // set value untuk draftKpBookUrl
                        document.getElementById('currentdraftKpBookUrl').value = draftKpBookUrl;
                        // Fungsi untuk menampilkan modal dan menampilkan PDF
                        window.openKpBookModal = function() {

                            // const kpBookModal = document.getElementById("kpBookModal");
                            // const kpBookFrame = document.getElementById("kpBookFrame");

                            const url = document.getElementById("currentKpBookUrl").value;
                            console.log("URL yang dimasukkan ke iframe:", url);
                            kpBookFrame.src = kpBookUrl;
                            kpBookModal.classList.remove("hidden"); // Tampilkan modal
                        };
                        window.openDraftKpBookModal = function() {

                            // const kpBookModal = document.getElementById("kpBookModal");
                            // const kpBookFrame = document.getElementById("kpBookFrame");

                            const url = document.getElementById("currentdraftKpBookUrl").value;
                            console.log("URL yang dimasukkan ke iframe:", url);
                            draftKpBookFrame.src = draftKpBookUrl;
                            draftKpBookModal.classList.remove("hidden"); // Tampilkan modal
                        };


                        // Tutup modal saat tombol "Batal" ditekan
                        // closeKpBookModalBtn.addEventListener("click", function() {
                        //     kpBookModal.classList.add("hidden");
                        // Kosongkan iframe saat modal ditutup
                        // kpBookFrame.src = "";
                        // });
                        document.getElementById("closeKpBookModal").addEventListener("click", function() {
                            // const kpBookModal = document.getElementById("kpBookModal");
                            // const kpBookFrame = document.getElementById("kpBookFrame");
                            kpBookModal.classList.add("hidden");
                            kpBookFrame.src = ""; // Kosongkan iframe
                        });
                        document.getElementById("closeDraftKpBookModal").addEventListener("click", function() {
                            // const kpBookModal = document.getElementById("kpBookModal");
                            // const kpBookFrame = document.getElementById("kpBookFrame");
                            draftKpBookModal.classList.add("hidden");
                            draftKpBookFrame.src = ""; // Kosongkan iframe
                        });
                    }
                }

                const isReadOnly = (feedback && feedback.trim() !== '') || (parseInt(rating) > 0);
                const isRevision = bookStatus === "Rejected";

                document.getElementById("feedbackModalTitle").textContent = isReadOnly ?
                    "Lihat Rating" :
                    isRevision ?
                    "Revisi" :
                    "Isi Pengalaman";

                document.getElementById("submitFeedbackBtn").classList.toggle("hidden", isReadOnly);
                document.getElementById("submitFeedbackBtn").classList.remove("hidden", isRevision);

                feedbackForm.querySelectorAll("input, textarea").forEach(el => {
                    if (el.id !== 'internshipId' && el.id !== 'modalCompanyId') {
                        isReadOnly ? el.setAttribute("readonly", true) : el.removeAttribute("readonly");
                    }
                });

                stars.forEach(star => {
                    star.style.pointerEvents = isReadOnly ? "none" : "auto";
                });

                feedbackModal.classList.remove("hidden");

                // Tutup modal feedback saat tombol "Batal" ditekan
                // closeModalFeedback

                // // Tutup modal saat klik di luar modal
                // window.addEventListener("click", function(event) {
                //     if (event.target === uploadKpModal) {
                //         uploadKpModal.classList.add("hidden");
                //     }
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

            // Validasi file PDF saat submit form
            feedbackForm.addEventListener("submit", function(event) {
                const internshipId = document.getElementById("internshipId").value;

                // Hanya validasi jika mode ISI (bukan Lihat Rating)
                if (!internshipId) {
                    const fileInput = feedbackForm.querySelector('input[type="file"]');
                    if (fileInput && fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        const fileType = file.type;

                        if (fileType !== "application/pdf") {
                            event.preventDefault();
                            alert("File buku KP atau surat pengalaman harus berupa PDF.");
                            return false;
                        }
                    } else {
                        // Tidak ada file diunggah
                        event.preventDefault();
                        alert("Silakan unggah file PDF untuk buku KP atau surat pengalaman.");
                        return false;
                    }
                }
            });

        });
    </script>
    <script>
        // Menghilangkan alert
        setTimeout(() => {
            document.getElementById('alert-success')?.remove();
            document.getElementById('alert-error')?.remove();
        }, 4000);
    </script>
@endsection
