@extends('admin.sidebaradmin')

@section('content')
<div class="bg-white shadow-md rounded p-6">
    <h1 class="text-2xl font-bold mb-4">Statistik Magang</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Grafik Mahasiswa per Perusahaan -->
        <div class="bg-gray-100 p-4 rounded">
            <h2 class="text-lg font-semibold mb-2">Jumlah Mahasiswa per Perusahaan</h2>
            <canvas id="companyChart"></canvas>
        </div>

        <!-- Grafik Status Magang -->
        <div class="bg-gray-100 p-4 rounded">
            <h2 class="text-lg font-semibold mb-2">Status Magang</h2>
            <canvas id="statusChart"></canvas>
        </div>

        <!-- Grafik Jumlah Magang per Bulan -->
        <div class="bg-gray-100 p-4 rounded col-span-1 md:col-span-2">
            <h2 class="text-lg font-semibold mb-2">Mahasiswa Magang per Bulan</h2>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik jumlah mahasiswa per perusahaan
    const companyChart = new Chart(document.getElementById('companyChart'), {
        type: 'bar',
        data: {
            labels: @json($companyStats->pluck('company')),
            datasets: [{
                label: 'Jumlah Mahasiswa',
                data: @json($companyStats->pluck('total')),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        }
    });

    // Data untuk grafik status magang
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: @json($statusStats->pluck('status')),
            datasets: [{
                data: @json($statusStats->pluck('total')),
                backgroundColor: ['#4CAF50', '#FF9800'],
            }]
        }
    });

    // Data untuk grafik jumlah magang per bulan
    const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyStats->pluck('month')),
            datasets: [{
                label: 'Jumlah Magang',
                data: @json($monthlyStats->pluck('total')),
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: true
            }]
        }
    });
</script>
@endsection
