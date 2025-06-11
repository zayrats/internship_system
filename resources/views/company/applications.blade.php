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

        <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
            <table id="selectionTable" class="w-full border text-sm">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="p-2 border">Nama Mahasiswa</th>
                        <th class="p-2 border">NRP/NIM</th>
                        <th class="p-2 border">Divisi</th>
                        <th class="p-2 border">Lampiran</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Tanggal pengajuan</th>
                        <th class="p-2 border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $application)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-2 border">{{ $application->student->name }}</td>
                            <td class="p-2 border">{{ $application->student->student_number }}</td>
                            <td class="p-2 border">{{ $application->vacancy->division }}</td>
                            <td class="p-2 border space-x-2">
                                <button class="text-blue-600 hover:underline"
                                    onclick="openDocumentModal('{{ $application->document }}')">
                                    📄 Pengantar
                                </button>
                                <button class="text-blue-600 hover:underline"
                                    onclick="openCvModal('{{ $application->student->cv }}')">
                                    📎 CV
                                </button>
                            </td>
                            <td class="p-2 border">
                                @php
                                    $statusColor = match ($application->status) {
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Approved' => 'bg-green-100 text-green-800',
                                        'Rejected' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $application->status }}
                                </span>
                            </td>
                            <td class="p-2 border">{{ $application->application_date }}</td>
                            <td class="p-2 border text-center space-x-1">
                                @if ($application->status === 'Pending')
                                    <form
                                        action="{{ route('applications.approve', ['id' => $application->application_id]) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                            ✅ Terima
                                        </button>
                                    </form>
                                    <button onclick="openRejectModal({{ $application->application_id }})"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                        ❌ Tolak
                                    </button>
                                @else
                                    <span class="text-gray-400 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Dokumen --}}
    <div id="modalDocument" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-3/4 lg:w-2/3">
            <h2 class="text-xl font-bold mb-4 text-center">📄 Dokumen Pengantar</h2>
            <iframe id="documentFrame" class="w-full h-[500px] rounded border"></iframe>
            <div class="text-center mt-4">
                <button onclick="closeDocumentModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal CV --}}
    <div id="modalCv" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-3/4 lg:w-2/3">
            <h2 class="text-xl font-bold mb-4 text-center">📎 CV Mahasiswa</h2>
            <iframe id="cvFrame" class="w-full h-[500px] rounded border"></iframe>
            <div class="text-center mt-4">
                <button onclick="closeCvModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Penolakan --}}
    <div id="modalReject" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h3 class="text-lg font-semibold mb-3">Alasan Penolakan</h3>
            <form id="rejectForm" method="POST" action="{{ route('applications.reject', ['id' => 0]) }}">
                @csrf
                <input type="hidden" name="application_id" id="application_id">
                <textarea name="rejection_reason" id="rejection_reason" class="w-full p-2 border rounded mb-3" rows="3"
                    placeholder="Masukkan alasan penolakan..." required></textarea>
                <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 mb-2">Kirim
                    Penolakan</button>
                <button type="button" onclick="closeRejectModal()"
                    class="w-full bg-gray-400 text-white py-2 rounded hover:bg-gray-500">Batal</button>
            </form>
        </div>
    </div>

    {{-- Script --}}
    <script>
        // Modal Dokumen
        function openDocumentModal(url) {
            document.getElementById('documentFrame').src = url;
            document.getElementById('modalDocument').classList.remove('hidden');
        }

        function closeDocumentModal() {
            document.getElementById('modalDocument').classList.add('hidden');
            document.getElementById('documentFrame').src = '';
        }

        // Modal CV
        function openCvModal(url) {
            document.getElementById('cvFrame').src = url;
            document.getElementById('modalCv').classList.remove('hidden');
        }

        function closeCvModal() {
            document.getElementById('modalCv').classList.add('hidden');
            document.getElementById('cvFrame').src = '';
        }

        // Modal Penolakan
        function openRejectModal(applicationId) {
            const form = document.getElementById('rejectForm');
            const route = "{{ route('applications.reject', ['id' => ':id']) }}".replace(':id', applicationId);
            form.action = route;
            document.getElementById('application_id').value = applicationId;
            document.getElementById('modalReject').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('modalReject').classList.add('hidden');
            document.getElementById('application_id').value = '';
            document.getElementById('rejection_reason').value = '';
        }

        // Hide alert
        setTimeout(() => {
            document.getElementById('alert-success')?.remove();
            document.getElementById('alert-error')?.remove();
        }, 4000);
    </script>
    <script>
        // DataTables Init
        $(document).ready(function() {
            $('#selectionTable').DataTable({
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
