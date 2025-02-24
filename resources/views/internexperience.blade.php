@extends('master')
@section('content')
<h3 class="bg-gray-100 dark:bg-gray-700 text-3xl font-bold text-gray-900 dark:text-gray-100 text-center py-5">Cerita Mahasiswa</h3>
    <div class="min-h-screen flex items-start justify-center bg-gray-100 dark:bg-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            @foreach ($data as $item)
                <div class="bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden dark:bg-gray-800 dark:border-gray-700 cursor-pointer"
                    onclick="openModal(this)"
                    data-title="{{ $item->title }}"
                    data-feedback="{{ $item->feedback }}"
                    data-company_logo="{{ asset('storage/' . $item->company_logo) }}"
                    data-student_name="{{ $item->student_name }}"
                    data-position="{{ $item->position }}"
                    data-start_date="{{ $item->start_date }}"
                    data-end_date="{{ $item->end_date }}"
                    data-duration="{{ \Carbon\Carbon::parse($item->start_date)->diffInMonths(\Carbon\Carbon::parse($item->end_date)) }} bulan"
                    data-company_address="{{ $item->company_address }}">

                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center">
                            {{ $item->title }}
                        </h3>
                        <p class="mt-3 text-gray-600 dark:text-gray-400 text-center">
                            "{{ $item->feedback }}"
                        </p>
                    </div>
                    <div class="flex flex-col items-center bg-gray-50 dark:bg-gray-700 p-4">
                        <img class="rounded-full w-14 h-14 border-2 border-gray-300 dark:border-gray-600"
                            src="{{ asset('storage/' . $item->company_logo) }}" alt="Company Logo">
                        <div class="mt-2 text-center">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $item->student_name }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $item->position }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
                <h2 class="text-xl font-bold mb-2">Detail Internship</h2>
                <img id="modalCompanyLogo" class="w-24 h-24 object-cover rounded mb-3" alt="Company Logo">
                <p><strong>Judul:</strong> <span id="modalTitle"></span></p>
                <p><strong>Feedback:</strong> <span id="modalFeedback"></span></p>
                <p><strong>Nama Mahasiswa:</strong> <span id="modalStudentName"></span></p>
                <p><strong>Posisi:</strong> <span id="modalPosition"></span></p>
                <p><strong>Durasi Magang:</strong> <span id="modalDuration"></span></p>
                <p><strong>Alamat Perusahaan:</strong> <span id="modalCompanyAddress"></span></p>
                <button id="closeModal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Tutup</button>
            </div>
        </div>

        <!-- Script untuk menampilkan modal -->
        <script>
            function openModal(element) {
                document.getElementById('modalTitle').innerText = element.getAttribute('data-title');
                document.getElementById('modalFeedback').innerText = element.getAttribute('data-feedback');
                document.getElementById('modalStudentName').innerText = element.getAttribute('data-student_name');
                document.getElementById('modalPosition').innerText = element.getAttribute('data-position');
                document.getElementById('modalDuration').innerText = element.getAttribute('data-duration');
                document.getElementById('modalCompanyAddress').innerText = element.getAttribute('data-company_address');

                let companyLogo = element.getAttribute('data-company_logo');
                document.getElementById('modalCompanyLogo').setAttribute('src', companyLogo);

                document.getElementById('detailModal').classList.remove('hidden');
            }

            document.getElementById('closeModal').addEventListener('click', function () {
                document.getElementById('detailModal').classList.add('hidden');
            });
        </script>




    </div>
@endsection
