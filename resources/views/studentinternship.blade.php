@extends('master')
@section('content')
    <div class="block  p-3 bg-white border border-gray-200  shadow-sm  dark:bg-gray-800 dark:border-gray-700 ">


        <h5 class="py-5 text-3xl font-bold tracking-tight  text-gray-900 dark:text-gray-100 text-center">Wujudkan
            Magang Impianmu</h5>

        <form class="max-w-md mx-auto">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Cari</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Mobile Developer" required />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cari</button>
            </div>
        </form>

    </div>
    @if (session('success'))
        <div class="bg-green-500 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div class="min-h-screen p-6 bg-gray-100 dark:bg-gray-700">
        <div class="container mx-auto">
            <!-- Sorting Buttons -->
            <div class="flex justify-end mb-4 space-x-2">
                <button onclick="sortTable('duration')" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Urutkan
                    berdasarkan durasi</button>
                <button onclick="sortTable('start_date')" class="px-4 py-2 bg-green-600 text-white rounded-lg">Urutkan
                    berdasarkan mulai magang</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="internshipTable">
                @foreach ($internships as $intern)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-5"
                        data-duration="{{ $intern->duration }}" data-start_date="{{ $intern->start_date }}">
                        <img class="rounded-t-lg h-32 w-full object-cover" src="{{ $intern->company_logo }}"
                            alt="Company Logo">
                        <h5 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $intern->company_name }}</h5>
                        <p class="text-gray-500 text-sm">{{ $intern->company_address }}</p>
                        <p class="text-base text-gray-700 dark:text-gray-300 mt-2 font-semibold">{{ $intern->division }} -
                            {{ $intern->type }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Durasi: <span
                                class="font-bold">{{ $intern->duration }}</span> Bulan</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Mulai Magang: {{ $intern->start_date }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Akhir Magang: {{ $intern->end_date }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <button
                                onclick="openApplyModal('{{ $student->user_id }}', '{{ $student->name }}', '{{ $student->student_number }}', '{{ $student->prodi }}', '{{ $student->department }}', '{{ $intern->vacancy_id }}')"
                                class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg px-4 py-2">Daftar
                            </button>
                            <button
                                onclick="openDetailModal('{{ $intern->company_name }}', '{{ $intern->company_logo }}', '{{ $intern->company_address }}', '{{ $intern->company_email }}', '{{ $intern->division }}', '{{ $intern->start_date }}', '{{ $intern->end_date }}', '{{ $intern->requirements }}', '{{ $intern->type }}')"
                                class="text-white bg-gray-600 hover:bg-gray-700 font-medium rounded-lg px-4 py-2">Selengkapnya</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal Pendaftaran -->
    <div id="applyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white text-center">Pendaftaran Magang</h3>
            <form id="applyForm" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="vacancy_id" id="vacancyId">

                <!-- Nama -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" id="modalName" disabled class="w-full p-2 border rounded">
                </div>

                <!-- NRP/NIM -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">NRP/NIM</label>
                    <input type="text" id="modalNrp" disabled class="w-full p-2 border rounded">
                </div>

                <!-- Program Studi -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Program Studi</label>
                    <input type="text" id="modalProdi" disabled class="w-full p-2 border rounded">
                </div>

                <!-- Departemen -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Departemen</label>
                    <input type="text" id="modalDepartment" disabled class="w-full p-2 border rounded">
                </div>

                <!-- Email -->
                <div class="mb-2">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="text" id="modalEmail" disabled class="w-full p-2 border rounded">
                </div>

                <!-- Upload Surat Lamaran -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Surat Lamaran (PDF)</label>
                    <input accept="application/pdf" type="file" name="document" class="w-full border rounded p-1 bg-white hover:bg-white-100" required>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-blue-700 text-white rounded-lg px-4 py-2">Submit</button>
            </form>

            <!-- Tombol Cancel -->
            <button onclick="closeModal('applyModal')" class="mt-4 text-red-500 w-full text-center">Cancel</button>
        </div>
    </div>


    <!-- Modal Detail -->
    <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-xl">
            <h3 class="text-lg font-semibold mb-4 text-center text-gray-900 dark:text-white">Detail Perusahaan</h3>
            <img id="detailCompanyLogo" class="mx-auto w-24 h-24 rounded-full">
            <p class="text-gray-900 dark:text-white"><strong>Nama Perusahaan:</strong> <span id="detailCompanyName"></span></p>
            <p class="text-gray-900 dark:text-white"><strong>Alamat:</strong> <span id="detailCompanyAddress"></span></p>
            <p class="text-gray-900 dark:text-white"><strong>Email:</strong> <span id="detailCompanyEmail"></span></p>

            <h3 class="text-lg font-semibold mt-4 text-center text-gray-700 dark:text-gray-300">Detail Lowongan</h3>
            <p class="text-gray-900 dark:text-white"><strong>Divisi:</strong> <span id="detailDivision"></span></p>
            <p class="text-gray-900 dark:text-white"><strong>Mulai Magang:</strong> <span id="detailStart"></span></p>
            <p class="text-gray-900 dark:text-white"><strong>Akhir Magang:</strong> <span id="detailEnd"></span></p>
            <p class="text-gray-900 dark:text-white"><strong>Persyaratan:</strong> <span id="detailRequirements"></span></p>
            <p class="text-gray-900 dark:text-white"><strong>Jenis:</strong> <span id="detailType"></span></p>

            <button onclick="closeModal('detailModal')"
                class="mt-4 bg-red-500 text-white px-4 py-2 rounded w-full">Tutup</button>
        </div>
    </div>

    <!-- Sorting dan Modal Script -->
    <script>
        function sortTable(type) {
            let table = document.getElementById("internshipTable");
            let items = Array.from(table.children);

            items.sort((a, b) => {
                let valA = a.dataset[type];
                let valB = b.dataset[type];

                return type === 'start_date' ? new Date(valA) - new Date(valB) : valA - valB;
            });

            items.forEach(item => table.appendChild(item));
        }

        function openApplyModal(userId, name, nrp, prodi, department, vacancyId) {
            // Set nilai ke input modal
            document.getElementById("modalName").value = name;
            document.getElementById("modalNrp").value = nrp;
            document.getElementById("modalProdi").value = prodi;
            document.getElementById("modalDepartment").value = department;
            document.getElementById("modalEmail").value = "{{ Auth::user()->email }}";
            document.getElementById("vacancyId").value = vacancyId;

            // Update action form dengan route yang benar
            let routeUrl = "{{ route('internshipapply', ['id' => ':id']) }}".replace(':id', vacancyId);
            document.getElementById("applyForm").action = routeUrl;

            // Tampilkan modal
            document.getElementById("applyModal").classList.remove("hidden");
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add("hidden");
        }



        function openDetailModal(name, logo, address, email, division, start, end, requirements, type) {
            document.getElementById("detailCompanyLogo").src = logo;
            document.getElementById("detailCompanyName").innerText = name;
            document.getElementById("detailCompanyAddress").innerText = address;
            document.getElementById("detailCompanyEmail").innerText = email;
            document.getElementById("detailDivision").innerText = division;
            document.getElementById("detailStart").innerText = start;
            document.getElementById("detailEnd").innerText = end;
            document.getElementById("detailRequirements").innerText = requirements;
            document.getElementById("detailType").innerText = type;

            document.getElementById("detailModal").classList.remove("hidden");
        }

        function closeModal(id) {
            document.getElementById(id).classList.add("hidden");
        }
    </script>
@endsection
