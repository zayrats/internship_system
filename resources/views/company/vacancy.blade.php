@extends('layouts.company-nav')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Lowongan Magang</h2>

    <!-- Tombol Tambah Lowongan -->
    <button onclick="openModal()" class="bg-blue-500 text-white p-2 rounded mb-4">Tambah Lowongan</button>

    <!-- Tabel Lowongan -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Divisi</th>
                    <th class="p-2 border">Durasi</th>
                    <th class="p-2 border">Jenis</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vacancies as $vacancy)
                <tr>
                    <td class="p-2 border">{{ $vacancy->division }}</td>
                    <td class="p-2 border">{{ $vacancy->duration }} bulan</td>
                    <td class="p-2 border">{{ $vacancy->type }}</td>
                    <td class="p-2 border">
                        <span class="px-2 py-1 rounded {{ $vacancy->status == 'aktif' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                            {{ ucfirst($vacancy->status) }}
                        </span>
                    </td>
                    <td class="p-2 border">
                        @if ($vacancy->applications_count == 0)
                            <button onclick="editVacancy({{ $vacancy }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                            <form action="{{ route('vacancy.delete', $vacancy->vacancy_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Hapus</button>
                            </form>
                        @else
                            <span class="text-gray-500">Tidak bisa diedit</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Lowongan -->
<div id="vacancyModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96">
        <h3 class="text-xl font-bold mb-4">Tambah/Edit Lowongan</h3>
        <form id="vacancyForm" method="POST">
            @csrf
            <input type="hidden" name="id" id="vacancyId">
            <label>Divisi</label>
            <input type="text" name="division" id="division" class="w-full p-2 border rounded mb-2" required>

            <label>Durasi (1-8 bulan)</label>
            <input type="number" name="duration" id="duration" min="1" max="8" class="w-full p-2 border rounded mb-2" required>

            <label>Jenis Magang</label>
            <select name="type" id="type" class="w-full p-2 border rounded mb-2">
                <option value="WHO">WHO</option>
                <option value="WFH">WFH</option>
            </select>

            <label>Persyaratan</label>
            <textarea name="requirements" id="requirements" class="w-full p-2 border rounded mb-2"></textarea>

            <label>Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="w-full p-2 border rounded mb-2" required>

            <label>Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="w-full p-2 border rounded mb-2" required>

            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Simpan</button>
            <button type="button" onclick="closeModal()" class="mt-2 bg-gray-500 text-white p-2 rounded w-full">Batal</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('vacancyModal').classList.remove('hidden');
    }
    function closeModal() {
        document.getElementById('vacancyModal').classList.add('hidden');
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
</script>

@endsection
