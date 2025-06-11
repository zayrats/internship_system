@extends('master')

@section('content')
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 via-gray-200 to-gray-300 dark:from-gray-800 dark:via-gray-900 dark:to-gray-950 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-6xl bg-white dark:bg-gray-900 rounded-xl shadow-xl p-8">
            <h1 class="text-center text-4xl font-extrabold text-gray-800 dark:text-gray-100 mb-8">
                Peta Persebaran Mahasiswa
            </h1>
            <div id="map" class="h-[500px] w-full rounded-lg shadow-inner border border-gray-300 dark:border-gray-700">
            </div>
        </div>
    </div>



    <style>
        #map {
            border: 2px solid #ccc;
            border-radius: 8px;
        }

        #map:hover {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
    </style>
    {{-- Leaflet CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" crossorigin=""></script>

    <script>
        let map, markers = [];

        function initMap() {
            map = L.map('map', {
                center: {
                    lat: -7.7925927,
                    lng: 110.3658812
                },
                zoom: 8
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
                const marker = generateMarker(data, index);
                marker.addTo(map).bindPopup(`<b>${data.name}</b>`);
                map.panTo(data.position);
                markers.push(marker);
            });
        }

        function generateMarker(data, index) {
            return L.marker(data.position, {
                    draggable: data.draggable
                })
                .on('click', e => markerClicked(e, index))
                .on('dragend', e => markerDragEnd(e, index));
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

        // Initialize on load
        document.addEventListener('DOMContentLoaded', initMap);
    </script>
@endsection
