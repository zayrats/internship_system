@extends('master')

@section('content')
    @if (session('success'))
        <div id="alert-success" class="bg-green-500 text-white p-3 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="bg-red-500 text-white p-3 rounded mb-4 text-center">
            {{ session('error') }}
        </div>
    @endif
    <div class="min-h-screen bg-gray-100 dark:bg-gray-800 py-10">
        <div class="container mx-auto max-w-4xl">
            <h3 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-6">Halaman Profil</h3>

            <div class="bg-white dark:bg-gray-900 shadow-xl rounded-lg p-8">
                <!-- Form Profil -->
                <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Foto Profil -->
                    <div class="flex items-center justify-center mb-6">
                        <div class="relative w-32 h-32">
                            <!-- Cek apakah ada foto profil, jika tidak gunakan default -->
                            <img id="profileImage"
                                src="{{ $student->profile_picture ? $student->profile_picture : asset('images/default_profile.jpg') }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover rounded-full border-4 border-gray-300 dark:border-gray-600">

                            <!-- Input file hidden -->
                            <input type="file" id="uploadProfile" name="profile_picture" class="hidden" accept="image/*">

                            <!-- Tombol upload -->
                            <label for="uploadProfile"
                                class="absolute bottom-0 right-0 bg-blue-500 p-2 rounded-full cursor-pointer">
                                📷
                            </label>
                        </div>
                    </div>



                    <div class="grid grid-cols-2 gap-4">
                        <!-- Nama -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $student->name) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('name')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NRP -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">NRP</label>
                            <input type="text" name="student_number"
                                value="{{ old('student_number', $student->student_number) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('student_number')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('email')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- No. HP -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">No. HP</label>
                            <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('phone')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Alamat</label>
                            <input type="text" name="address" value="{{ old('address', $student->address) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('address')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Instagram</label>
                            <input type="text" name="instagram" value="{{ old('instagram', $student->instagram) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('instagram')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Tanggal Lahir</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate', $student->birthdate) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('birthdate')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Gender</label>
                            <select name="gender"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                                <option value="Laki-laki"
                                    {{ old('gender', $student->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="Perempuan"
                                    {{ old('gender', $student->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Program Studi -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Program Studi</label>
                            <select name="prodi"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                                <option value="D3" {{ old('prodi', $student->prodi) == 'D3' ? 'selected' : '' }}>D3
                                </option>
                                <option value="D4" {{ old('prodi', $student->prodi) == 'D4' ? 'selected' : '' }}>D4
                                </option>
                            </select>
                            @error('prodi')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Departemen -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Departemen</label>
                            <input type="text" name="department" value="{{ old('department', $student->department) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('department')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Masuk -->
                        <div>
                            <label class="text-gray-700 dark:text-gray-300">Tahun Masuk</label>
                            <input type="number" name="year" value="{{ old('year', $student->year) }}"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white">
                            @error('year')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- password --}}
                        <div>
                            <label for="password" class="text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" id="password"
                                class="w-full p-2 border rounded bg-gray-100 dark:bg-gray-800 dark:text-white"
                                placeholder="Masukkan password baru (opsional)">
                            <p class="text-xs text-gray-500 mt-1">Biarkan jika tidak ingin mengubah password.</p>
                        </div>

                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-green-500 text-white p-2 rounded">Simpan</button>
                    </div>
                    <!-- Upload CV -->
                    <div class="mt-6 flex justify-end">
                        <div class="flex items-center space-x-2">
                            <input type="file" name="cv" accept=".pdf" class="hidden" id="uploadCv">
                            <label for="uploadCv" class="bg-purple-500 text-white px-4 py-2 rounded cursor-pointer">
                                {{ $student->cv ? 'Update CV' : 'Upload CV' }}
                            </label>

                            @if ($student->cv)
                                <a href="{{ asset('storage/cv' . $student->cv) }}" download class="text-blue-500  bg-slate-500 text-white px-4 py-2 rounded cursor-pointer">Lihat
                                    CV</a>
                            @endif
                            <!-- Tempat preview nama file -->
                            <span id="cvFileName" class="text-sm text-gray-600 dark:text-gray-300 mt-2 md:mt-0"></span>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <script>
        document.getElementById('uploadCv')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileNameSpan = document.getElementById('cvFileName');

            if (file) {
                if (file.type !== 'application/pdf') {
                    alert('CV harus berupa file PDF.');
                    e.target.value = '';
                    fileNameSpan.textContent = ''; // kosongkan preview
                    return;
                }

                fileNameSpan.textContent = `File dipilih: ${file.name}`;
            } else {
                fileNameSpan.textContent = '';
            }
        });
    </script>

    <script>
        // Menghilangkan alert
        setTimeout(() => {
            document.getElementById('alert-success')?.remove();
            document.getElementById('alert-error')?.remove();
        }, 4000);
    </script>
    <script>
        document.getElementById('uploadProfile').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Cek apakah file adalah gambar
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('profileImage').src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Silakan pilih file gambar yang valid (JPG, PNG, WebP).');
                    event.target.value = ''; // Reset input file
                }
            }
        });
    </script>
@endsection
