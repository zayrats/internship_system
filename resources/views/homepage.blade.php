@extends('master')

@section('content')
    <div
        class="min-h-screen px-4 py-10 bg-gradient-to-br from-indigo-100 via-white to-cyan-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 gap-10">
            <!-- Hero -->
            <div
                class="relative bg-white/70 dark:bg-gray-800/70 shadow-2xl rounded-3xl overflow-hidden transition hover:scale-[1.02] hover:shadow-2xl duration-300 ease-in-out">
                <div class="p-8 md:p-10 relative z-10">
                    <h1
                        class="text-4xl font-extrabold text-gray-900 dark:text-white leading-snug mb-4 tracking-tight animate-fade-in-up">
                        🌟 Kisahmu Jadi Bekalku
                    </h1>
                    <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed mb-6 animate-fade-in-up delay-100">
                        Temukan kisah inspiratif dari para senior, cari referensi magang yang tepat, dan buat jejakmu
                        sendiri!
                    </p>
                    <a href="{{ route('internship') }}"
                        class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-gradient-to-r from-indigo-500 to-cyan-500 text-white font-semibold shadow-lg transition hover:scale-105 hover:shadow-xl active:scale-95 duration-300">
                        ✍️ Ayo Buat Kisahmu
                        <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <img class="absolute inset-0 w-full h-full object-cover opacity-20"
                    src="https://www.pens.ac.id/wp-content/uploads/2023/12/cover-web-ionic-1080x675.jpg" alt="internship" />
            </div>

            {{-- timeline KP --}}
            @if (isset($user->role), 'Mahasiswa')
                <ol class="items-center sm:flex">
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pra-Kerja Praktek </h3>
                            <ol class="list-disc ml-6 text-base font-normal text-gray-500 dark:text-gray-400">
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Sosialisasi KP</li>
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Pendaftaran dan
                                    Pengusulan
                                    Tempat KP</li>
                            </ol>
                        </div>
                    </li>
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pembekalan Kerja Praktek</h3>
                            <ol class="list-disc ml-6 text-base font-normal text-gray-500 dark:text-gray-400">
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Dibimbing oleh:
                                    Koordinator
                                    KP,Kaprodi,Dosen Pembimbing.</li>
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Mahasiswa menyerahkan
                                    surat
                                    pengantar ke pembimbing perusahaan.</li>
                            </ol>
                        </div>
                    </li>
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pelaksanaan Kerja Praktek</h3>
                            <ol class="list-disc ml-6 text-base font-normal text-gray-500 dark:text-gray-400">
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Mahasiswa: Mengunggah
                                    progres
                                    laporan KP minimal 1 kali/minggu.
                                </li>
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Pembimbing Perusahaan dan
                                    Dosen PENS: Memverifikasi dan memberikan feedback mingguan.</li>
                            </ol>
                        </div>
                    </li>
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Penilaian dan Seminar KP
                            </h3>
                            <ol class="list-disc ml-6 text-base font-normal text-gray-500 dark:text-gray-400">
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Penilaian KP

                                    Oleh:
                                    Pembimbing Perusahaan (50%)
                                    Dosen Pembimbing (35%)
                                    Dosen Penguji (15%)
                                </li>
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">
                                    Melaksanakan seminar (sidang) KP sesuai jadwal.</li>
                            </ol>
                        </div>
                    </li>
                    <li class="relative mb-6 sm:mb-0">
                        <div class="flex items-center">
                            <div
                                class="z-10 flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full ring-0 ring-white dark:bg-blue-900 sm:ring-8 dark:ring-gray-900 shrink-0">
                                <svg class="w-2.5 h-2.5 text-blue-800 dark:text-blue-300" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700"></div>
                        </div>
                        <div class="mt-3 sm:pe-8">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pengumpulan Buku KP
                            </h3>
                            <ol class="list-disc ml-6 text-base font-normal text-gray-500 dark:text-gray-400">
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Revisi berdasarkan
                                    masukan saat seminar.</li>
                                <li class="text-base font-normal text-gray-500 dark:text-gray-400">Pengumpulan Laporan</li>
                            </ol>
                        </div>
                    </li>

                </ol>
            @endif

            <!-- Map Full Width -->
            <div class="mt-16">
                <h2 class="text-4xl font-bold text-center text-gray-800 dark:text-white mb-6 animate-fade-in-up">🗺️ Peta
                    Persebaran Mahasiswa</h2>
                <div class="w-full h-[600px] rounded-xl overflow-hidden shadow-2xl border border-gray-300 dark:border-gray-700 animate-fade-in-up delay-150"
                    id="map"></div>
            </div>
        </div>
    </div>

    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>

    {{-- Animasi CSS --}}
    <style>
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.7s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-150 {
            animation-delay: 0.15s;
        }

        #map:hover {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease-in-out;
        }
    </style>

    {{-- Map logic --}}
    <script>
        let map, markers = [];

        function initMap() {
            map = L.map('map', {
                center: {
                    lat: -7.7925927,
                    lng: 110.3658812
                },
                zoom: 8,
                zoomControl: true
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            map.on('click', mapClicked);
            initMarkers();
        }

        function initMarkers() {
            const initialMarkers = @json($initialMarkers);

            initialMarkers.forEach((data, index) => {
                const marker = L.marker(data.position, {
                        draggable: data.draggable
                    })
                    .addTo(map)
                    .bindPopup(`
                        <div>
                            <strong>${data.name}</strong><br>
                            Total Mahasiswa: ${data.total_intern}<br>
                            ${data.students.length > 0
                            ? 'Nama Mahasiswa:<ul>' + data.students.map(s => `<li>${s}</li>`).join('') + '</ul>' : 'Belum ada mahasiswa'}
                        </div>
                    `)
                    .on('click', e => markerClicked(e, index))
                    .on('dragend', e => markerDragEnd(e, index));

                map.panTo(data.position);
                markers.push(marker);
            });
        }

        function mapClicked(e) {
            console.log(e.latlng.lat, e.latlng.lng);
        }

        function markerClicked(e, index) {
            console.log(e.latlng.lat, e.latlng.lng);
        }

        function markerDragEnd(e, index) {
            console.log(e.target.getLatLng());
        }

        document.addEventListener('DOMContentLoaded', initMap);
    </script>
@endsection
