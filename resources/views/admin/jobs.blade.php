@extends('admin.sidebaradmin')

@section('content')
    <div class="bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Manajemen Lowongan Perusahaan</h1>

        @if (session('success'))
            <div class="bg-green-500 text-white p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div id="alert-error" class="bg-red-500 text-white p-3 rounded mb-4 text-center shadow">
                {{ session('error') }}
            </div>
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

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Filter
            </button>
        </form>

        <!-- Tabel Lowongan -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border">Posisi</th>
                        <th class="p-2 border">Perusahaan</th>
                        <th class="p-2 border">Tanggal Mulai</th>
                        <th class="p-2 border">Tanggal Berakhir</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr class="text-center">
                            <td class="p-2 border">{{ $job->division }}</td>
                            <td class="p-2 border">{{ $job->company->name ?? '-' }}</td>
                            {{-- @dump($job->company) --}}
                            <td class="p-2 border">{{ $job->start_date }}</td>
                            <td class="p-2 border">{{ $job->end_date }}</td>
                            <td class="p-2 border space-x-2">
                                <!-- Lihat -->
                                <button onclick='viewJob(@json($job))'
                                    class="bg-blue-500 text-white px-3 py-1 rounded inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Lihat
                                </button>

                                @if ($job->approval !== 'Approved' && $job->approval !== 'Rejected')
                                    <form action="{{ route('admin.jobs.update', $job->vacancy_id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="approval" value="Approved">
                                        <button type="submit"
                                            class="bg-green-500 text-white px-3 py-1 rounded">Terima</button>
                                    </form>

                                    <form action="{{ route('admin.jobs.update', $job->vacancy_id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="approval" value="Rejected">
                                        <button type="submit"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded">Tolak</button>
                                    </form>
                                @endif

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

    <!-- Modal -->
    <div id="jobModal"
        class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 opacity-0 transition duration-300 transform scale-95">
        <div class="bg-white p-6 rounded-lg w-1/2">
            <h2 class="text-xl font-bold mb-4">Detail Lowongan</h2>
            <p><strong>Divisi:</strong> <span id="modalDivision"></span></p>
            <p><strong>Perusahaan:</strong> <span id="modalCompany"></span></p>
            <p><strong>Durasi:</strong> <span id="modalDuration"></span></p>
            <p><strong>Tipe:</strong> <span id="modalType"></span></p>
            <p><strong>Persyaratan:</strong> <span id="modalRequirement"></span></p>
            <p><strong>Tanggal Mulai:</strong> <span id="modalStart"></span></p>
            <p><strong>Tanggal Selesai:</strong> <span id="modalEnd"></span></p>
            <p><strong>Status:</strong>
                <span id="modalStatus" class="inline-block px-2 py-1 rounded text-white text-sm"></span>
            </p>
            <p><strong>Pengajuan Magang:</strong> <span id="modalApproval"></span></p>
            <div class="mt-4 flex justify-end">
                <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function viewJob(job) {
            const modal = document.getElementById('jobModal');
            document.getElementById('modalDivision').innerText = job.division;
            document.getElementById('modalCompany').innerText = job.company?.name ?? '-';
            document.getElementById('modalStart').innerText = job.start_date;
            document.getElementById('modalEnd').innerText = job.end_date;
            document.getElementById('modalDuration').innerText = job.duration + ' bulan';
            document.getElementById('modalType').innerText = job.type;
            document.getElementById('modalRequirement').innerText = job.requirements;
            document.getElementById('modalApproval').innerText = job.status;

            const statusText = job.approval ?? 'Pending';
            const modalStatus = document.getElementById('modalStatus');
            modalStatus.innerText = statusText;
            modalStatus.className = 'inline-block px-2 py-1 rounded text-white text-sm';
            if (statusText === 'Approved') modalStatus.classList.add('bg-green-500');
            else if (statusText === 'Rejected') modalStatus.classList.add('bg-red-500');
            else modalStatus.classList.add('bg-yellow-500');

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'scale-95');
                modal.classList.add('opacity-100', 'scale-100');
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('jobModal');
            modal.classList.remove('opacity-100', 'scale-100');
            modal.classList.add('opacity-0', 'scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>
@endsection
