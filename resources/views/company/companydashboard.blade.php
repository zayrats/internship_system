@extends('layouts.company-nav')

@section('content')
    <!-- Leaflet JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Profil Perusahaan</h2>

        <!-- Form Profil Perusahaan -->
        <form action="{{ route('actionsavecompany') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            <!-- Nama Perusahaan -->
            <label class="block text-gray-700">Nama Perusahaan</label>
            <input type="text" name="name" value="{{ $company->name ?? '' }}" class="w-full p-2 border rounded mb-4"
                required>

            <!-- Logo Perusahaan -->
            <label class="block text-gray-700">Logo Perusahaan (Maks. 2MB)</label>
            <div class="flex items-center space-x-4">
                <img id="logoPreview" src="{{ $company->logo ?? asset('images/default-logo.png') }}"
                    class="w-24 h-24 object-cover rounded border">
                <input type="file" id="logoUpload" name="logo" class="hidden" accept="image/*">
                <label for="logoUpload" class="bg-blue-500 text-white p-2 rounded cursor-pointer">Pilih Logo</label>
            </div>

            <!-- Alamat -->
            <label class="block text-gray-700 mt-4">Alamat</label>
            <textarea name="address" class="w-full p-2 border rounded">{{ $company->address ?? '' }}</textarea>

            <!-- Contact Email & Phone -->
            <label class="block text-gray-700 mt-4">Email Kontak</label>
            <input type="email" name="contact_email" value="{{ $company->contact_email ?? '' }}"
                class="w-full p-2 border rounded">

            <label class="block text-gray-700 mt-4">Nomor Telepon</label>
            <input type="text" name="contact_phone" value="{{ $company->contact_phone ?? '' }}"
                class="w-full p-2 border rounded">

            <!-- Deskripsi Perusahaan -->
            <label class="block text-gray-700 mt-4">Deskripsi</label>
            <textarea name="description" class="w-full p-2 border rounded">{{ $company->description ?? '' }}</textarea>

            <!-- Peta Interaktif -->
            <label class="block text-gray-700 mt-4">Lokasi Perusahaan</label>
            <div id="map" class="w-full h-64 border rounded"></div>
            <input type="hidden" id="x_coordinate" name="x_coordinate" value="{{ $company->x_coordinate ?? '' }}">
            <input type="hidden" id="y_coordinate" name="y_coordinate" value="{{ $company->y_coordinate ?? '' }}">

            <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded w-full">Simpan</button>
        </form>
    </div>

    <!-- Script Preview Logo & Peta -->
    <script>
        // Preview Logo Saat Dipilih
        document.getElementById('logoUpload').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('logoPreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });

        // Peta Interaktif (Menggunakan Leaflet.js)
        var map = L.map('map').setView([{{ $company->x_coordinate ?? -6.2 }},
            {{ $company->y_coordinate ?? 106.816666 }}
        ], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        var marker = L.marker([{{ $company->x_coordinate ?? -6.2 }}, {{ $company->y_coordinate ?? 106.816666 }}], {
            draggable: true
        }).addTo(map);

        marker.on('dragend', function(e) {
            document.getElementById('x_coordinate').value = marker.getLatLng().lat;
            document.getElementById('y_coordinate').value = marker.getLatLng().lng;
        });
    </script>
@endsection
