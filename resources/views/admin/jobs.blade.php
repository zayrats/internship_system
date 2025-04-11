@extends('admin.sidebaradmin')

@section('content')
    <div class="bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen Lowongan Perusahaan</h1>

        <!-- Notifikasi -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif

        <!-- Filter & Pencarian -->
        <form method="GET" action="{{ route('admin.jobs') }}" class="flex gap-4 mb-4">
            <input type="text" name="search" placeholder="Cari Posisi Kerja" class="border p-2 rounded w-1/3"
                value="{{ request('search') }}">

            <select name="company_id" class="border p-2 rounded w-1/3">
                <option value="">Semua Perusahaan</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->company_id }}"
                        {{ request('company_id') == $company->company_id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border p-2 rounded w-1/3">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="berakhir" {{ request('status') == 'berakhir' ? 'selected' : '' }}>Berakhir</option>
            </select>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </form>


        <!-- Tabel Lowongan -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border">Posisi</th>
                        <th class="p-2 border">Perusahaan</th>
                        {{-- <th class="p-2 border">Kuota</th> --}}
                        <th class="p-2 border">Tanggal Mulai</th>
                        <th class="p-2 border">Tanggal Berakhir</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr class="text-center">
                            <td class="p-2 border">{{ $job->division }}</td>
                            <td class="p-2 border">{{ $company->name }}</td>
                            {{-- <td class="p-2 border">{{ $job->quota }}</td> --}}
                            <td class="p-2 border">{{ $job->start_date }}</td>
                            <td class="p-2 border">{{ $job->end_date }}</td>
                            <td class="p-2 border space-x-2">
                                <!-- Lihat -->
                                <button onclick="viewJob({{ $job->toJson() }})"
                                    class="bg-blue-500 text-white px-3 py-1 rounded">Lihat</button>

                                <!-- Terima & Tolak hanya muncul jika belum disetujui atau ditolak -->
                                @if ($job->approval !== 'Approved' && $job->approval !== 'Rejected')
                                    <!-- Terima -->
                                    <form action="{{ route('admin.jobs.update', $job->vacancy_id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="approval" value="Approved">
                                        <button type="submit"
                                            class="bg-green-500 text-white px-3 py-1 rounded">Terima</button>
                                    </form>

                                    <!-- Tolak -->
                                    <form action="{{ route('admin.jobs.update', $job->vacancy_id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="approval" value="Rejected">
                                        <button type="submit"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded">Tolak</button>
                                    </form>
                                @endif

                                <!-- Hapus -->
                                <form action="{{ route('admin.jobs.delete', $job->vacancy_id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Hapus</button>
                                </form>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $jobs->links() }}
        </div>
    </div>
    <!-- Modal Lihat Detail -->
    <div id="jobModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-1/2">
            <h2 class="text-xl font-bold mb-4">Detail Lowongan</h2>
            <p><strong>Divisi:</strong> <span id="modalDivision"></span></p>
            <p><strong>Perusahaan:</strong> <span id="modalCompany"></span></p>
            <p><strong>Durasi:</strong> <span id="modalDuration"></span></p>
            <p><strong>Tipe:</strong> <span id="modalType"></span></p>
            <p><strong>Persyaratan:</strong> <span id="modalRequirement"></span></p>
            <p><strong>Tanggal Mulai:</strong> <span id="modalStart"></span></p>
            <p><strong>Tanggal Selesai:</strong> <span id="modalEnd"></span></p>
            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
            <p><strong>Pengajuan Magang:</strong> <span id="modalApproval"></span></p>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function viewJob(job) {
            document.getElementById('modalDivision').innerText = job.division;
            document.getElementById('modalCompany').innerText = job.company.name;
            document.getElementById('modalStart').innerText = job.start_date;
            document.getElementById('modalEnd').innerText = job.end_date;
            document.getElementById('modalDuration').innerText = job.duration;
            document.getElementById('modalType').innerText = job.type;
            document.getElementById('modalRequirement').innerText = job.requirements;
            document.getElementById('modalStatus').innerText = job.approval;
            document.getElementById('modalApproval').innerText = job.status;
            document.getElementById('jobModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('jobModal').classList.add('hidden');
        }
    </script>
@endsection
