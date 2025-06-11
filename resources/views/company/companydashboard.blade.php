@extends('layouts.company-nav')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <div class="max-w-5xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-black mb-6">Profil Perusahaan</h2>

        <!-- Alert Success/Error -->
        @if (session('success'))
            <div id="alert-success"
                class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                role="alert">
                <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.293-5.707a1 1 0 011.414 0L15 9.414l-1.414-1.414L9 12.586 6.707 10.293 5.293 11.707 9 15.414l4.293-4.293a1 1 0 00-1.414-1.414L9 12.586z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error"
                class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800"
                role="alert">
                <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 13a1 1 0 01-1 1H3a1 1 0 010-2h14a1 1 0 011 1zM18 9a1 1 0 01-1 1H3a1 1 0 010-2h14a1 1 0 011 1z"
                        clip-rule="evenodd" />
                </svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('actionsavecompany') }}" method="POST" enctype="multipart/form-data"
            class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md space-y-6">
            @csrf

            <!-- Nama Perusahaan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Perusahaan</label>
                <input type="text" name="name" value="{{ $company->name ?? '' }}"
                    class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500"
                    required>
            </div>

            <!-- Logo -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Logo Perusahaan (Maks.
                    2MB)</label>
                <div class="flex items-center space-x-4">
                    <img id="logoPreview" src="{{ $company->logo ?? asset('images/default-logo.png') }}"
                        class="w-24 h-24 object-cover rounded border">
                    <input type="file" id="logoUpload" name="logo" class="hidden" accept="image/*">
                    <label for="logoUpload"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition">Pilih
                        Logo</label>
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                <textarea name="address" rows="3"
                    class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">{{ $company->address ?? '' }}</textarea>
            </div>

            <!-- Email dan Telepon -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ $company->contact_email ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor Telepon</label>
                    <input type="tel" name="contact_phone" value="{{ $company->contact_phone ?? '' }}"
                        class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi
                    Perusahaan</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:text-white border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500">{{ $company->description ?? '' }}</textarea>
            </div>

            <!-- Peta Lokasi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Lokasi Perusahaan</label>
                <div id="map" class="w-full h-64 border rounded-lg overflow-hidden"></div>
                <input type="hidden" id="x_coordinate" name="x_coordinate" value="{{ $company->x_coordinate ?? '' }}">
                <input type="hidden" id="y_coordinate" name="y_coordinate" value="{{ $company->y_coordinate ?? '' }}">
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                Simpan
            </button>
        </form>
    </div>

    <!-- Script Logo Preview -->
    <script>
        document.getElementById('logoUpload').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('logoPreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        // Alert Dismiss
        setTimeout(() => {
            document.getElementById('alert-success')?.remove();
            document.getElementById('alert-error')?.remove();
        }, 4000);
    </script>

    <!-- Script Map -->
    <script>
        const lat = {{ $company->y_coordinate ?? -6.2 }};
        const lng = {{ $company->x_coordinate ?? 106.816666 }};
        const map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        const marker = L.marker([lat, lng], {
            draggable: true
        }).addTo(map);
        marker.on('dragend', function(e) {
            const pos = marker.getLatLng();
            document.getElementById('y_coordinate').value = pos.lat;
            document.getElementById('x_coordinate').value = pos.lng;
        });

        map.on('click', function(e) {
            const {
                lat,
                lng
            } = e.latlng;
            marker.setLatLng([lat, lng]);
            document.getElementById('y_coordinate').value = lat;
            document.getElementById('x_coordinate').value = lng;
        });
    </script>
@endsection
