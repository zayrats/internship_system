@extends('admin.sidebaradmin')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <h1 class="text-2xl font-bold mb-4">Monitoring Mahasiswa Magang</h1>

    <!-- Filter dan Pencarian -->
    <form method="GET" action="{{ route('admin.monitoring') }}" class="flex gap-4 mb-4">
        <input type="text" name="search" placeholder="Cari Nama / NRP"
               class="border p-2 rounded w-1/3" value="{{ request('search') }}">

        <select name="company_id" class="border p-2 rounded w-1/3">
            <option value="">Semua Perusahaan</option>
            @foreach($companies as $company)
                <option value="{{ $company->company_id }}" {{ request('company_id') == $company->company_id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>

        <select name="status" class="border p-2 rounded w-1/3">
            <option value="">Semua Status</option>
            <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dimulai</option>
            <option value="sedang" {{ request('status') == 'sedang' ? 'selected' : '' }}>Sedang Berjalan</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <!-- Tabel Mahasiswa -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">NRP</th>
                    <th class="p-2 border">Program Studi</th>
                    <th class="p-2 border">Perusahaan</th>
                    <th class="p-2 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($internships as $intern)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $intern->name }}</td>
                        <td class="p-2 border">{{ $intern->student_number }}</td>
                        <td class="p-2 border">{{ $intern->prodi }}</td>
                        <td class="p-2 border">{{ $intern->company_name }}</td>
                        <td class="p-2 border">
                            @php
                                $now = now();
                                $status = 'Belum Dimulai';
                                if ($intern->start_date <= $now && $intern->end_date >= $now) {
                                    $status = 'Sedang Berjalan';
                                } elseif ($intern->end_date < $now) {
                                    $status = 'Selesai';
                                }
                            @endphp
                            <span class="px-2 py-1 text-white rounded
                                {{ $status == 'Belum Dimulai' ? 'bg-yellow-500' : ($status == 'Sedang Berjalan' ? 'bg-green-500' : 'bg-red-500') }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $internships->links() }}
    </div>
</div>
@endsection
