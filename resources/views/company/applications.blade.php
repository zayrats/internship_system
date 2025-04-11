@extends('layouts.company-nav')

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
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Seleksi Mahasiswa</h2>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border">Nama Mahasiswa</th>
                        <th class="p-2 border">NRP/NIM</th>
                        <th class="p-2 border">Divisi</th>
                        <th class="p-2 border">Lampiran</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td class="p-2 border">{{ $application->student->name }}</td>
                            <td class="p-2 border">{{ $application->student->student_number }}</td>
                            <td class="p-2 border">{{ $application->vacancy->division }}</td>
                            <td class="p-2 border"><button class="text-yellow-500 hover:underline"
                                    onclick="openDocumentModal('{{ $application->document }}')">Pengantar</button>
                                <button class="text-yellow-500 hover:underline"
                                    onclick="opencvModal('{{ $application->student->cv }}')">
                                    CV
                                </button>
                            </td>
                            <td class="p-2 border">
                                <span
                                    class="px-2 py-1 rounded
                            @if ($application->status == 'Pending') bg-yellow-500 text-white
                            @elseif ($application->status == 'Approved') bg-green-500 text-white
                            @else bg-red-500 text-white @endif">
                                    {{ $application->status }}
                                </span>
                            </td>
                            <td class="p-2 border">
                                @if ($application->status == 'Pending')
                                    <form
                                        action="{{ route('applications.approve', ['id' => $application->application_id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-500 text-white px-2 py-1 rounded">Terima</button>
                                    </form>
                                    <button onclick="rejectApplication({{ $application->application_id }})"
                                        class="bg-red-500 text-white px-2 py-1 rounded">Tolak</button>
                                @else
                                    <span class="text-gray-500">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- modal document --}}
        <div id="documentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                <h2 class="text-lg text-center font-bold text-center mb-4 text-gray-900 dark:text-white ">Dokumen
                    Pengantar</h2>

                <iframe id="documentFrame" class="w-full h-[500px]"></iframe>

                <div class="flex justify-center mt-6">
                    <button id="closeDocumentModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        <!-- modal cv -->
        <div id="cvModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                <h2 class="text-lg text-center font-bold text-center mb-4 text-gray-900 dark:text-white ">CV</h2>

                <iframe id="cvFrame" class="w-full h-[500px]"></iframe>

                <div class="flex justify-center mt-6">
                    <button id="closeCvModal" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penolakan -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold mb-4">Alasan Penolakan</h3>
            <form id="rejectForm" action="{{ route('applications.reject', ['id' => $application->application_id]) }}" method="POST">
                @csrf
                <input type="hidden" name="application_id" id="application_id">
                <textarea name="rejection_reason" id="rejection_reason" class="w-full p-2 border rounded mb-2" required></textarea>
                <button type="submit" class="bg-red-500 text-white p-2 rounded w-full">Tolak</button>
                <button type="button" onclick="closeRejectModal()" class="mt-2 bg-gray-500 text-white p-2 rounded w-full">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function approveApplication(application_id) {
            console.log(application_id);
            const url = `/company/applications/${application_id}/approve`;
            console.log("URL yang dikirim:", url); // Debugging

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({}) // Kirim payload kosong jika tidak ada data
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(() => location.reload()) // Refresh halaman jika sukses
                .catch(error => console.error('Error:', error)); // Tangani error
        }

        function rejectApplication(application_id) {
            document.getElementById('application_id').value = application_id;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const documentFrame = document.getElementById("documentFrame");
            const documentModal = document.getElementById('documentModal');
            const closeDocumentModalBtn = document.getElementById('closeDocumentModal');

            // Elemen di dalam modal
            window.openDocumentModal = function(documentUrl) {
                documentFrame.src = documentUrl;
                documentModal.classList.remove('hidden');
            };

            closeDocumentModalBtn.addEventListener('click', function() {
                documentModal.classList.add('hidden');
                documentFrame.src = "";
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cvFrame = document.getElementById("cvFrame");
            const cvModal = document.getElementById('cvModal');
            const closeCvModalBtn = document.getElementById('closeCvModal');

            // Elemen di dalam modal
            window.opencvModal = function(cvUrl) {
                cvFrame.src = cvUrl;
                cvModal.classList.remove('hidden');
            };

            closeCvModalBtn.addEventListener('click', function() {
                cvModal.classList.add('hidden');
                cvFrame.src = "";
            });
        });
    </script>
@endsection
