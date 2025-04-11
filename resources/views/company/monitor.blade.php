@extends('layouts.company-nav')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Pantau Mahasiswa Magang</h2>

    <div class="mb-4">
        <label for="statusFilter" class="font-semibold">Filter Status:</label>
        <select id="statusFilter" class="p-2 border rounded" onchange="filterStatus()">
            <option value="all">Semua</option>
            <option value="Approved">Sedang Magang</option>
            <option value="Finished">Selesai</option>
        </select>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Nama Mahasiswa</th>
                    <th class="p-2 border">NRP/NIM</th>
                    <th class="p-2 border">Divisi</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody id="studentTable">
                @foreach ($applications as $application)
                <tr data-status="{{ $application->status }}">
                    <td class="p-2 border">{{ $application->student->name }}</td>
                    <td class="p-2 border">{{ $application->student->student_number}}</td>
                    <td class="p-2 border">{{ $application->vacancy->division }}</td>
                    <td class="p-2 border">
                        <span class="px-2 py-1 rounded
                            @if ($application->status == 'Approved') bg-green-500 text-white
                            @elseif ($application->status == 'Finished') bg-blue-500 text-white
                            @endif">
                            {{ $application->status == 'Approved' ? 'Sedang Magang' : 'Selesai' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function filterStatus() {
        let filter = document.getElementById('statusFilter').value;
        let rows = document.querySelectorAll("#studentTable tr");

        rows.forEach(row => {
            let status = row.getAttribute("data-status");
            if (filter === "all" || status === filter) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>

@endsection
