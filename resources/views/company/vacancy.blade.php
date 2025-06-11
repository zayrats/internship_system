@extends('layouts.company-nav')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-semibold text-gray-800">Daftar Lowongan Magang</h2>
        <button onclick="openModal()" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            <i class="fas fa-plus"></i> Tambah Lowongan
        </button>
    </div>

    @if(session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="p-3">Divisi</th>
                    <th class="p-3">Durasi</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach ($vacancies as $vacancy)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-3">{{ $vacancy->division }}</td>
                    <td class="p-3">{{ $vacancy->duration }} bulan</td>
                    <td class="p-3">{{ $vacancy->type }}</td>
                    <td class="p-3">
                        <span class="inline-block px-2 py-1 text-xs rounded font-medium
                            {{ $vacancy->status === 'aktif' ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                            {{ ucfirst($vacancy->status) }}
                        </span>
                    </td>
                    <td class="p-3">
                        @if ($vacancy->applications_count == 0)
                            <button onclick='editVacancy(@json($vacancy))' class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow">Edit</button>
                            <form action="{{ route('vacancy.delete', $vacancy->vacancy_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow">Hapus</button>
                            </form>
                        @else
                            <span class="text-gray-400 italic">Tidak bisa diedit</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Lowongan -->
<div id="vacancyModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
        <h3 class="text-xl font-bold mb-4 text-gray-800">Form Lowongan</h3>
        <form id="vacancyForm" method="POST">
            @csrf
            <input type="hidden" name="id" id="vacancyId">

            <div class="mb-3">
                <label class="block mb-1 font-medium">Divisi</label>
                <input type="text" name="division" id="division" class="w-full border-gray-300 rounded p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Durasi (1-8 bulan)</label>
                <input type="number" name="duration" id="duration" min="1" max="8" class="w-full border-gray-300 rounded p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Jenis Magang</label>
                <select name="type" id="type" class="w-full border-gray-300 rounded p-2">
                    <option value="WHO">WHO</option>
                    <option value="WFH">WFH</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Persyaratan</label>
                <textarea name="requirements" id="requirements" class="w-full border-gray-300 rounded p-2" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" class="w-full border-gray-300 rounded p-2" required>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Tanggal Selesai</label>
                <input type="date" name="end_date" id="end_date" class="w-full border-gray-300 rounded p-2" required>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Simpan</button>
                <button type="button" onclick="closeModal()" class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 rounded">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('vacancyModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('vacancyModal').classList.add('hidden');
        document.getElementById('vacancyForm').reset();
        document.getElementById('vacancyId').value = '';
    }

    function editVacancy(vacancy) {
        document.getElementById('vacancyId').value = vacancy.id;
        document.getElementById('division').value = vacancy.division;
        document.getElementById('duration').value = vacancy.duration;
        document.getElementById('type').value = vacancy.type;
        document.getElementById('requirements').value = vacancy.requirements;
        document.getElementById('start_date').value = vacancy.start_date;
        document.getElementById('end_date').value = vacancy.end_date;
        openModal();
    }

    document.getElementById('vacancyForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const id = document.getElementById('vacancyId').value;
        const action = id
            ? `{{ url('/company/vacancy/update') }}/${id}`
            : `{{ route('vacancy.store') }}`;
        const method = id ? 'PUT' : 'POST';

        const hiddenMethod = document.createElement('input');
        hiddenMethod.type = 'hidden';
        hiddenMethod.name = '_method';
        hiddenMethod.value = method;

        form.action = action;
        if (id) form.appendChild(hiddenMethod);
        form.submit();
    });

    document.getElementById('start_date').addEventListener('change', function () {
        const start = this.value;
        document.getElementById('end_date').min = start;
    });

    setTimeout(() => {
        document.getElementById('alert-success')?.remove();
        document.getElementById('alert-error')?.remove();
    }, 4000);
</script>
@endsection
