@extends('layouts.company-nav')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">Pantau Mahasiswa Magang</h2>

        {{-- Filter --}}
        <div class="mb-6">
            <label for="statusFilter" class="block text-sm font-medium mb-2">Filter Status:</label>
            <select id="statusFilter" class="p-2 border rounded w-full md:w-64" onchange="filterStatus()">
                <option value="all">Semua</option>
                <option value="Approved">Sedang Magang</option>
                <option value="Finished">Selesai</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
            <table id="internTable" class="min-w-full border text-sm">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700">
                        <th class="p-2 border">Nama Mahasiswa</th>
                        <th class="p-2 border">NRP/NIM</th>
                        <th class="p-2 border">Divisi</th>
                        <th class="p-2 border">Periode Magang</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody id="studentTable">
                    @forelse ($applications as $application)
                        <tr class="hover:bg-gray-50 transition" data-status="{{ $application->status }}">
                            <td class="p-2 border">{{ $application->student->name }}</td>
                            <td class="p-2 border">{{ $application->student->student_number }}</td>
                            <td class="p-2 border">{{ $application->vacancy->division }}</td>
                            <td class="p-2 border">{{ $application->vacancy->start_date }} -
                                {{ $application->vacancy->end_date }}</td>
                            <td class="p-2 border">
                                @php
                                    $statusLabel = $application->status == 'Approved' ? 'Sedang Magang' : 'Selesai';
                                    $statusColor =
                                        $application->status == 'Approved'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-blue-100 text-blue-700';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-4 text-gray-500">Belum ada mahasiswa yang magang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Script --}}
    <script>
        $(document).ready(function() {
            $('#internTable').DataTable({
                responsive: true,
                paging: true,
                ordering: true,
                info: true,
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

        function filterStatus() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll("#studentTable tr[data-status]");

            rows.forEach(row => {
                const status = row.getAttribute("data-status");
                row.style.display = (filter === "all" || status === filter) ? "" : "none";
            });
        }
    </script>
@endsection
