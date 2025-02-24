@extends('master')
@section('content')
    <div class="min-h-screen  justify-center bg-gray-100 dark:bg-gray-700">
        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center py-5">Halaman Profil</h3>
        <div class="container mx-auto p-20 pt-5">
            <!-- Card 1: Edit Profil -->
            <div class="bg-white shadow-lg rounded-xl p-12 mb-6">
                <h2 class="text-2xl font-semibold mb-4">Edit Profil</h2>
                <form id="editProfileForm" method="POST" action="/update-profile">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <label class="block text-gray-700">Nama</label>
                    <input type="text" name="name" value="{{ $student->name }}" class="w-full p-2 border rounded"
                        disabled>

                    <label class="block text-gray-700 mt-2">NRP</label>
                    <input type="text" name="student_number" value="{{ $student->student_number }}"
                        class="w-full p-2 border rounded" disabled>

                    <label class="block text-gray-700 mt-2">Email</label>
                    <input type="email" name="email" value="{{ $student->email }}" class="w-full p-2 border rounded"
                        disabled>

                    <label class="block text-gray-700 mt-2">Password</label>
                    <input type="password" name="password" placeholder="********" class="w-full p-2 border rounded"
                        disabled>

                    <label class="block text-gray-700 mt-2">Prodi</label>
                    <select name="prodi" class="w-full p-2 border rounded" disabled>
                        <option value="D3" {{ $student->prodi == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ $student->prodi == 'D4' ? 'selected' : '' }}>D4</option>
                    </select>

                    <label class="block text-gray-700 mt-2">Departemen</label>
                    <input type="text" name="department" value="{{ $student->department }}"
                        class="w-full p-2 border rounded" disabled>

                    <label class="block text-gray-700 mt-2">Angkatan</label>
                    <input type="number" name="year" value="{{ $student->year }}" class="w-full p-2 border rounded"
                        disabled>

                    <button type="button" id="editButton" class="mt-4 bg-blue-500 text-white p-2 rounded">Edit</button>
                    <button type="submit" id="saveButton"
                        class="mt-4 bg-green-500 text-white p-2 rounded hidden">Simpan</button>
                </form>
            </div>

            <!-- Card 2: Feedback Magang -->
            <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
                <h2 class="text-2xl font-semibold mb-4">Feedback Magang</h2>
                <form method="POST" action="/submit-feedback">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <label class="block text-gray-700">Perusahaan</label>
                    <select name="company_id" class="w-full p-2 border rounded">
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>

                    <label class="block text-gray-700 mt-2">Judul Buku KP</label>
                    <input type="text" name="title" class="w-full p-2 border rounded">

                    <label class="block text-gray-700 mt-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded">

                    <label class="block text-gray-700 mt-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded">

                    <label class="block text-gray-700 mt-2">Posisi Magang</label>
                    <input type="text" name="position" class="w-full p-2 border rounded">

                    <label class="block text-gray-700 mt-2">Feedback</label>
                    <textarea name="feedback" class="w-full p-2 border rounded"></textarea>

                    <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">Kirim</button>
                </form>
            </div>

            <!-- Upload Buku KP -->
            <button id="uploadButton" class="bg-purple-500 text-white p-3 rounded">Upload Buku KP</button>

            <!-- Modal Upload -->
            <div id="uploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded shadow-lg">
                    <h2 class="text-lg font-semibold mb-4">Upload Buku KP</h2>
                    <form method="POST" action="/upload-kp" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="file" name="file" accept=".pdf" class="w-full p-2 border rounded">
                        <button type="submit" class="mt-4 bg-green-500 text-white p-2 rounded">Upload</button>
                        <button type="button" id="closeModal"
                            class="mt-4 bg-gray-500 text-white p-2 rounded">Batal</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('editButton').addEventListener('click', function() {
                document.querySelectorAll('input, select').forEach(el => el.removeAttribute('disabled'));
                document.getElementById('editButton').classList.add('hidden');
                document.getElementById('saveButton').classList.remove('hidden');
            });

            document.getElementById('uploadButton').addEventListener('click', function() {
                document.getElementById('uploadModal').classList.remove('hidden');
            });

            document.getElementById('closeModal').addEventListener('click', function() {
                document.getElementById('uploadModal').classList.add('hidden');
            });
        </script>

    </div>
@endsection
