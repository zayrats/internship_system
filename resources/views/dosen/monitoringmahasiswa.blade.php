@extends('admin.sidebaradmin')

@section('content')
    <div class="bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 3v3.75H6a.75.75 0 00-.75.75V18a.75.75 0 00.75.75h12a.75.75 0 00.75-.75V7.5a.75.75 0 00-.75-.75h-3.75V3m-6 9.75h6" />
            </svg>
            Monitoring Mahasiswa Magang
        </h1>

        <!-- Filter dan Pencarian -->
        <form method="GET" action="{{ route('admin.monitoring') }}" class="flex flex-wrap gap-4 mb-4">
            <input type="text" name="search" placeholder="Cari Nama / NRP" class="border p-2 rounded w-full md:w-1/4"
                value="{{ request('search') }}">

            <select name="company_id" class="border p-2 rounded w-full md:w-1/4">
                <option value="">Semua Perusahaan</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->company_id }}"
                        {{ request('company_id') == $company->company_id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border p-2 rounded w-full md:w-1/4">
                <option value="">Semua Status</option>
                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dimulai</option>
                <option value="sedang" {{ request('status') == 'sedang' ? 'selected' : '' }}>Sedang Berjalan</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>

            <button type="submit" class="bg-blue-500 hover:bg-blue-600 transition text-white px-4 py-2 rounded">
                Filter
            </button>
        </form>

        <!-- Tabel Mahasiswa -->
        <div class="overflow-x-auto">
            <table id="internTable" class="min-w-full bg-white border rounded shadow-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-3 border">Nama</th>
                        <th class="p-3 border">NRP</th>
                        <th class="p-3 border">Program Studi</th>
                        <th class="p-3 border">Perusahaan</th>
                        <th class="p-3 border">Periode Magang</th>
                        <th class="p-3 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($internships as $intern)
                        @php
                            $now = now();
                            $status = 'Belum Dimulai';
                            $color = 'bg-yellow-500';
                            $icon = '⏳';

                            if ($intern->start_date <= $now && $intern->end_date >= $now) {
                                $status = 'Sedang Berjalan';
                                $color = 'bg-green-500';
                                $icon = '✅';
                            } elseif ($intern->end_date < $now) {
                                $status = 'Selesai';
                                $color = 'bg-red-500';
                                $icon = '📦';
                            }
                        @endphp
                        <tr class="text-center hover:bg-gray-50 transition">
                            <td class="p-3 border">{{ $intern->name }}</td>
                            <td class="p-3 border">{{ $intern->student_number }}</td>
                            <td class="p-3 border">{{ $intern->prodi }}</td>
                            <td class="p-3 border">{{ $intern->company_name }}</td>
                            <td class="p-3 border">{{ $intern->start_date }} - {{ $intern->end_date }}</td>
                            <td class="p-3 border">
                                <span
                                    class="inline-flex items-center gap-2 text-white px-3 py-1 rounded-full text-sm font-medium {{ $color }} transition duration-300">
                                    <span>{{ $icon }}</span>
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach

                    @if ($internships->count() === 0)
                        <tr>
                            <td colspan="5" class="text-center p-4 text-gray-500">
                                Tidak ada data ditemukan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $internships->links() }}
        </div>
    </div>
    <script>
        // DataTables Init
        $(document).ready(function() {
            $('#internTable').DataTable({
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
