@extends('dosen.sidebar')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-4">
    <h1 class="text-2xl font-bold text-blue-700 mb-6">Penjadwalan Sidang KP</h1>

    <form method="GET" class="flex flex-wrap gap-4 mb-6">
        <select name="periode" class="p-2 border rounded">
            <option value="">-- Pilih Periode --</option>
            @foreach ($periodes as $p)
                <option value="{{ $p }}" @selected(request('periode') == $p)>{{ $p }}</option>
            @endforeach
        </select>

        <select name="semester" class="p-2 border rounded">
            <option value="">-- Pilih Semester --</option>
            @foreach ($semesters as $s)
                <option value="{{ $s }}" @selected(request('semester') == $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>

        <select name="prodi" class="p-2 border rounded">
            <option value="">-- Pilih Prodi --</option>
            @foreach ($prodis as $p)
                <option value="{{ $p }}" @selected(request('prodi') == $p)>{{ $p }}</option>
            @endforeach
        </select>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
    </form>

    @if($internships->count() === 0)
        <p class="text-red-600">Tidak ada mahasiswa yang ditemukan.</p>
    @else
        <div class="overflow-x-auto bg-white dark:bg-gray-800 p-4 rounded shadow oferflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-200">
                        <th class="p-2">Nama Mahasiswa</th>
                        <th class="p-2">Perusahaan</th>
                        <th class="p-2">Prodi</th>
                        <th class="p-2">Periode</th>
                        <th class="p-2">Semester</th>
                        <th class="p-2">Tanggal Sidang</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($internships as $data)
                    {{-- @dd($data->internship->periode) --}}
                        <tr class="border-b dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200">
                            <td class="p-2">{{ $data->student->name }}</td>
                            <td class="p-2">{{$data->vacancy->company->name}}</td>
                            <td class="p-2">{{ $data->student->prodi }}</td>
                            <td class="p-2">{{ $data->periode }}</td>
                            <td class="p-2 capitalize">{{ $data->semester }}</td>
                            <td class="p-2">
                                <form action="{{ route('sidang.update', $data->application_id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="date" name="tanggal_sidang" value="{{ $data->tanggal_sidang }}"
                                        class="border rounded p-1 text-sm">
                                    <button class="bg-green-600 text-white text-sm px-2 py-1 rounded hover:bg-green-700">Simpan</button>
                                </form>
                            </td>
                            <td class="p-2">
                                <span class="text-gray-600 dark:text-gray-400">
                                    {{ $data->tanggal_sidang ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
