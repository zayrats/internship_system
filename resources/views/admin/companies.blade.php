@extends('admin.sidebaradmin')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Kelola Perusahaan & Lowongan</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-4 mb-6">
            <button data-modal-target="modalAddCompany" data-modal-toggle="modalAddCompany"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow transition">
                + Tambah Perusahaan
            </button>
            <button data-modal-target="modalAddVacancy" data-modal-toggle="modalAddVacancy"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow transition">
                + Tambah Lowongan
            </button>
        </div>

        {{-- Tabel Perusahaan --}}
        <div class="bg-white rounded-lg shadow p-4 mb-10">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Perusahaan</h2>
            <div class="overflow-x-auto">
                <table id="companiesTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Telepon</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($companies as $company)
                            <tr>
                                <td class="px-6 py-4">{{ $company->name }}</td>
                                <td class="px-6 py-4">{{ $company->email }}</td>
                                <td class="px-6 py-4">{{ $company->phone }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <!-- Tombol Edit -->
                                    <button type="button" data-modal-target="modalEditCompany-{{ $company->company_id }}"
                                        data-modal-toggle="modalEditCompany-{{ $company->company_id }}"
                                        class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                        Edit
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.companies.destroy', $company->company_id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus perusahaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Lowongan --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Daftar Lowongan</h2>
            <div class="overflow-x-auto">
                <table id="vacanciesTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                        <tr>
                            <th class="px-6 py-3 text-left">Perusahaan</th>
                            <th class="px-6 py-3 text-left">Divisi</th>
                            <th class="px-6 py-3 text-left">Jenis</th>
                            <th class="px-6 py-3 text-left">Durasi</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($vacancies as $vacancy)
                            <tr>
                                <td class="px-6 py-4">{{ $vacancy->company->name }}</td>
                                <td class="px-6 py-4">{{ $vacancy->division }}</td>
                                <td class="px-6 py-4">{{ strtoupper($vacancy->type) }}</td>
                                <td class="px-6 py-4">{{ $vacancy->duration }} bulan</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                {{ $vacancy->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($vacancy->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-2">
                                    <!-- Tombol Ulasan - hanya tampil jika ada internship -->
                                    @if ($vacancy->internships->count() > 0)
                                        <button type="button" data-modal-target="modalReviews-{{ $vacancy->vacancy_id }}"
                                            data-modal-toggle="modalReviews-{{ $vacancy->vacancy_id }}"
                                            class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                                            Ulasan ({{ $vacancy->internships->count() }})
                                        </button>
                                    @endif

                                    <!-- Tombol Edit -->
                                    <button type="button" data-modal-target="modalEditVacancy-{{ $vacancy->vacancy_id }}"
                                        data-modal-toggle="modalEditVacancy-{{ $vacancy->vacancy_id }}"
                                        class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">
                                        Edit
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.vacancies.destroy', $vacancy->vacancy_id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Ulasan - hanya untuk vacancy ini -->
                            @if ($vacancy->internships->count() > 0)
                                <div id="modalReviews-{{ $vacancy->vacancy_id }}" tabindex="-1" aria-hidden="true"
                                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative p-4 w-full max-w-4xl max-h-full">
                                        <div class="relative bg-white rounded-lg shadow">
                                            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                                                <h3 class="text-xl font-semibold text-gray-900">
                                                    Ulasan Mahasiswa - {{ $vacancy->division }}
                                                    <span
                                                        class="text-sm text-gray-500">({{ $vacancy->internships->count() }}
                                                        ulasan)</span>
                                                </h3>
                                                <button type="button"
                                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                                                    data-modal-hide="modalReviews-{{ $vacancy->vacancy_id }}">
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                    </svg>
                                                    <span class="sr-only">Close modal</span>
                                                </button>
                                            </div>
                                            <div class="p-4 md:p-5 space-y-4 max-h-96 overflow-y-auto">
                                                @foreach ($vacancy->internships as $internship)
                                                    <div class="border-b pb-4 mb-4 last:border-b-0">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <h4 class="font-medium text-gray-900">
                                                                        {{ $internship->student->name ?? 'N/A' }}
                                                                    </h4>
                                                                    <span
                                                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                                                                        {{ $internship->student->student_number ?? 'N/A' }}
                                                                    </span>
                                                                </div>

                                                                @if ($internship->feedback)
                                                                    <div class="mb-2">
                                                                        <p class="text-sm text-gray-600 mb-1">
                                                                            <strong>Feedback:</strong>
                                                                        </p>
                                                                        <p
                                                                            class="text-gray-700 bg-gray-50 p-2 rounded text-sm">
                                                                            {{ $internship->feedback }}
                                                                        </p>
                                                                    </div>
                                                                @endif

                                                                <div class="text-xs text-gray-500">
                                                                    <p>Periode:
                                                                        {{ $internship->start_date ? \Carbon\Carbon::parse($internship->start_date)->format('d M Y') : 'N/A' }}
                                                                        -
                                                                        {{ $internship->end_date ? \Carbon\Carbon::parse($internship->end_date)->format('d M Y') : 'N/A' }}
                                                                    </p>
                                                                    <p>Status:
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                                    {{ $internship->status == 'completed'
                                                                        ? 'bg-green-100 text-green-800'
                                                                        : ($internship->status == 'active'
                                                                            ? 'bg-blue-100 text-blue-800'
                                                                            : 'bg-gray-100 text-gray-800') }}">
                                                                            {{ ucfirst($internship->status) }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div class="flex flex-col gap-2 ml-4">
                                                                @if ($internship->kp_book)
                                                                    <button type="button"
                                                                        onclick="openPdfPreview('{{ asset($internship->kp_book) }}')"
                                                                        class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 whitespace-nowrap">
                                                                        📄 Buku KP
                                                                    </button>
                                                                @endif

                                                                @if ($internship->certificate)
                                                                    <button type="button"
                                                                        onclick="openPdfPreview('{{ asset('storage/' . $internship->kp_book) }}')"
                                                                        class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 whitespace-nowrap">
                                                                        🏆 Sertifikat
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($companies as $company)
        <!-- Modal Edit Perusahaan -->
        <div id="modalEditCompany-{{ $company->company_id }}" tabindex="-1"
            class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg">
                    <!-- Header -->
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Edit Perusahaan</h2>
                            <p class="text-sm text-gray-500">Ubah informasi perusahaan berikut.</p>
                        </div>
                        <button type="button" data-modal-hide="modalEditCompany-{{ $company->company_id }}"
                            class="text-gray-400 hover:text-gray-600 text-xl">×</button>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.companies.update', $company->company_id) }}" method="POST"
                        enctype="multipart/form-data" class="px-6 py-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                                <input type="text" name="name" value="{{ $company->name }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ $company->email }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telepon</label>
                                <input type="text" name="phone" value="{{ $company->phone }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input type="text" name="address" value="{{ $company->address }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" rows="3" class="w-full mt-1 p-2 border rounded-md">{{ $company->description }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Koordinat X</label>
                                <input type="text" name="x_coordinate" value="{{ $company->x_coordinate }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Koordinat Y</label>
                                <input type="text" name="y_coordinate" value="{{ $company->y_coordinate }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Logo Perusahaan (Opsional)</label>
                            <input type="file" name="logo" accept="image/*"
                                class="w-full mt-1 p-2 border rounded-md" />
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" data-modal-hide="modalEditCompany-{{ $company->company_id }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    @foreach ($vacancies as $vacancy)
        <div id="modalEditVacancy-{{ $vacancy->vacancy_id }}" tabindex="-1"
            class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg">
                    <!-- Header -->
                    <div class="border-b px-6 py-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Edit Lowongan</h2>
                            <p class="text-sm text-gray-500">Perbarui informasi lowongan.</p>
                        </div>
                        <button type="button" data-modal-hide="modalEditVacancy-{{ $vacancy->vacancy_id }}"
                            class="text-gray-400 hover:text-gray-600 text-xl">×</button>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.vacancies.update', $vacancy->vacancy_id) }}" method="POST"
                        class="px-6 py-6 space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Divisi</label>
                                <input type="text" name="division" value="{{ $vacancy->division }}" required
                                    class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Durasi (bulan)</label>
                                <input type="number" name="duration" value="{{ $vacancy->duration }}" min="1"
                                    max="8" required class="w-full mt-1 p-2 border rounded-md" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Magang</label>
                                <select name="type" class="w-full mt-1 p-2 border rounded-md">
                                    <option value="WFH" {{ $vacancy->type == 'WFH' ? 'selected' : '' }}>WFH</option>
                                    <option value="WFO" {{ $vacancy->type == 'WFO' ? 'selected' : '' }}>WFO</option>
                                    <option value="Hybrid" {{ $vacancy->type == 'Hybrid' ? 'selected' : '' }}>Hybrid
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" class="w-full mt-1 p-2 border rounded-md">
                                    <option value="aktif" {{ $vacancy->status == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="tutup" {{ $vacancy->status == 'tutup' ? 'selected' : '' }}>Tutup
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $vacancy->start_date }}" required
                                class="w-full mt-1 p-2 border rounded-md" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ $vacancy->end_date }}" required
                                class="w-full mt-1 p-2 border rounded-md" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Persyaratan</label>
                            <textarea name="requirements" rows="3" class="w-full mt-1 p-2 border rounded-md">{{ $vacancy->requirements }}</textarea>
                        </div>

                        <!-- Footer -->
                        <div class="flex justify-end mt-6 space-x-3">
                            <button type="button" data-modal-hide="modalEditVacancy-{{ $vacancy->vacancy_id }}"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <!-- PDF Preview Modal -->
    <div id="pdfPreviewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded shadow-lg w-11/12 md:w-3/4 lg:w-2/3 h-[85vh] flex flex-col">
            <h2 class="text-xl font-bold mb-4 text-center">📄 Preview Dokumen</h2>
            <iframe id="pdfPreviewFrame" class="w-full flex-1 border rounded"></iframe>
            <div class="text-center mt-4">
                <button onclick="closePdfPreview()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Perusahaan -->
    <div id="modalAddCompany" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg">
                <!-- Header -->
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Tambah Perusahaan Baru</h2>
                        <p class="text-sm text-gray-500">Lengkapi data berikut untuk menambahkan perusahaan.</p>
                    </div>
                    <button type="button" data-modal-hide="modalAddCompany"
                        class="text-gray-400 hover:text-gray-600 text-xl">×</button>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data"
                    class="px-6 py-6 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                            <input type="text" name="name" required
                                class="w-full mt-1 p-2 border rounded-md focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required
                                class="w-full mt-1 p-2 border rounded-md focus:ring focus:ring-blue-300" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="text" name="phone" required class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" name="address" required class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" required rows="3" class="w-full mt-1 p-2 border rounded-md resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Koordinat X</label>
                            <input type="text" name="x_coordinate" required
                                class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Koordinat Y</label>
                            <input type="text" name="y_coordinate" required
                                class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Logo Perusahaan</label>
                        <input type="file" name="logo" accept="image/*"
                            class="w-full mt-1 p-2 border rounded-md" />
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end mt-6 space-x-3">
                        <button type="button" data-modal-hide="modalAddCompany"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan
                            Perusahaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal Tambah Lowongan -->
    <div id="modalAddVacancy" tabindex="-1" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg">
                <!-- Header -->
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Tambah Lowongan Magang</h2>
                        <p class="text-sm text-gray-500">Lengkapi informasi lowongan magang di bawah ini.</p>
                    </div>
                    <button type="button" data-modal-hide="modalAddVacancy"
                        class="text-gray-400 hover:text-gray-600 text-xl">×</button>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.companies.store-vacancy') }}" method="POST" class="px-6 py-6 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Perusahaan</label>
                        <select name="company_id" required class="w-full mt-1 p-2 border rounded-md">
                            <option value="" disabled selected>-- Pilih Perusahaan --</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->company_id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Divisi</label>
                            <input type="text" name="division" required class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Durasi (bulan)</label>
                            <input type="number" name="duration" required class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Magang</label>
                        <select name="type" required class="w-full mt-1 p-2 border rounded-md">
                            <option value="WFH">WFH</option>
                            <option value="WFO">WFO</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" required class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="date" name="end_date" required class="w-full mt-1 p-2 border rounded-md" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Persyaratan</label>
                        <textarea name="requirements" required rows="3" class="w-full mt-1 p-2 border rounded-md resize-none"></textarea>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end mt-6 space-x-3">
                        <button type="button" data-modal-hide="modalAddVacancy"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-md">Simpan
                            Lowongan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('#companiesTable').DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: "Cari perusahaan...",
                    search: "",
                    lengthMenu: "_MENU_ entri per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data tersedia",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            $('#vacanciesTable').DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: "Cari lowongan...",
                    search: "",
                    lengthMenu: "_MENU_ entri per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data tersedia",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });
        });
    </script>
    <script>
        function openPdfPreview(url) {
            document.getElementById('pdfPreviewFrame').src = url;
            document.getElementById('pdfPreviewModal').classList.remove('hidden');
        }

        function closePdfPreview() {
            document.getElementById('pdfPreviewModal').classList.add('hidden');
            document.getElementById('pdfPreviewFrame').src = '';
        }
    </script>
@endsection
