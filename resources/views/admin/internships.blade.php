@extends('admin.sidebaradmin')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <h1 class="text-2xl font-bold mb-4">Rekapitulasi Magang</h1>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <!-- Filter & Pencarian -->
    <form method="GET" action="{{ route('admin.internships') }}" class="flex gap-4 mb-4">
        <input type="text" name="search" placeholder="Cari Nama Mahasiswa"
               class="border p-2 rounded w-1/3" value="{{ request('search') }}">

        <select name="company_id" class="border p-2 rounded w-1/3">
            <option value="">Semua Perusahaan</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>

        <select name="status" class="border p-2 rounded w-1/3">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <!-- Tombol Ekspor -->
    <a href="{{ route('admin.internships.export') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Ekspor ke Excel</a>

    <!-- Tabel Rekapitulasi Magang -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Nama Mahasiswa</th>
                    <th class="p-2 border">NRP</th>
                    <th class="p-2 border">Perusahaan</th>
                    <th class="p-2 border">Mulai</th>
                    <th class="p-2 border">Selesai</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($internships as $internship)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $internship->student }}</td>
                        <td class="p-2 border">{{ $internship->nrp }}</td>
                        <td class="p-2 border">{{ $internship->company }}</td>
                        <td class="p-2 border">{{ $internship->start_date }}</td>
                        <td class="p-2 border">{{ $internship->end_date }}</td>
                        <td class="p-2 border">
                            <span class="px-3 py-1 rounded {{ $internship->status == 'aktif' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white' }}">
                                {{ ucfirst($internship->status) }}
                            </span>
                        </td>
                        <td class="p-2 border">
                            <button onclick="viewDetails({{ $internship }})" class="bg-yellow-500 text-white px-3 py-1 rounded">Detail</button>
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
