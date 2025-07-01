@extends('dosen.sidebar')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4">
        <div id="loadingIndicator" class="hidden flex justify-center items-center mt-4">
            <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-500 border-solid"></div>
        </div>

        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📊 Dashboard KP Dosen</h1>
            <div class="flex space-x-3">
                <form method="GET" id="filterForm" class="flex space-x-3">
                    <select name="periode" onchange="document.getElementById('filterForm').submit()"
                        class="p-2 rounded-lg border bg-white dark:bg-gray-800 dark:text-white shadow">
                        <option value="">Pilih Periode</option>
                        @foreach ($periode as $p)
                            <option value="{{ $p }}" {{ request('periode') == $p ? 'selected' : '' }}>
                                {{ $p }}</option>
                        @endforeach
                    </select>

                    <select name="prodi" onchange="document.getElementById('filterForm').submit()"
                        class="p-2 rounded-lg border bg-white dark:bg-gray-800 dark:text-white shadow">
                        <option value="">Pilih Prodi</option>
                        @foreach ($prodi as $p)
                            <option value="{{ $p }}" {{ request('prodi') == $p ? 'selected' : '' }}>
                                {{ $p }}</option>
                        @endforeach
                    </select>

                    <select name="semester" onchange="document.getElementById('filterForm').submit()"
                        class="p-2 rounded-lg border bg-white dark:bg-gray-800 dark:text-white shadow">
                        <option value="">Semester</option>
                        @foreach ($semester as $s)
                            <option value="{{ $s }}" {{ request('semester') == $s ? 'selected' : '' }}>
                                {{ $s }}</option>
                        @endforeach
                    </select>
                </form>

            </div>
        </div>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            {{-- Total Mahasiswa --}}
            <div style="background-color: #1447e6" class=" p-5 rounded-xl shadow flex items-center space-x-4">
                <div class="text-blue-500 text-3xl">👨‍🎓</div>
                <div>
                    <h2 class="text-sm font-medium text-gray-600 dark:text-white">Total Mahasiswa</h2>
                    <p class="text-2xl font-bold text-blue-700 dark:text-white">{{ $totalMahasiswa }}</p>
                </div>
            </div>

            {{-- Sudah KP --}}
            <div style="background-color: #00a63e" class=" p-5 rounded-xl shadow flex items-center space-x-4">
                <div class="text-green-500 text-3xl">✅</div>
                <div>
                    <h2 class="text-sm font-medium text-gray-600 dark:text-white">Selesai KP</h2>
                    <p class="text-2xl font-bold text-green-700 dark:text-white">{{ $sudahKP }}</p>
                </div>
            </div>

            {{-- Sedang KP --}}
            <div style="background-color: #efb100" class=" p-5 rounded-xl shadow flex items-center space-x-4">
                <div class="text-yellow-500 text-3xl">⏳</div>
                <div>
                    <h2 class="text-sm font-medium text-gray-600 dark:text-white">Sedang KP</h2>
                    <p class="text-2xl font-bold text-yellow-700 dark:text-white">{{ $sedangKP }}</p>
                </div>
            </div>

            {{-- Belum KP --}}
            <div style="background-color: #e7000b" class=" p-5 rounded-xl shadow flex items-center space-x-4">
                <div class="text-red-500 text-3xl">❌</div>
                <div>
                    <h2 class="text-sm font-medium text-gray-600 dark:text-white">Belum KP</h2>
                    <p class="text-2xl font-bold text-red-700 dark:text-white">{{ $belumKP }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 p-2 bg-gray-100 dark:bg-gray-900 rounded-xl">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10">
                {{-- Chart 1: Feedback Mahasiswa --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-4 text-center text-gray-800 dark:text-white">
                        Feedback Mahasiswa Setelah KP
                    </h2>
                    <canvas id="feedbackPieChart" class="w-full h-64"></canvas>
                </div>

                {{-- Chart 2: Distribusi KP --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-4 text-center text-gray-800 dark:text-white">
                        Distribusi KP Mahasiswa
                    </h2>
                    <canvas id="pieChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>
        <div class="mt-10">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Rekap Mahasiswa KP per Prodi</h3>
            <canvas id="prodiChart" height="100"></canvas>
        </div>

        <div id="detailSection" class="hidden mt-8">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-white">Detail Mahasiswa</h3>
            <div class="overflow-auto rounded-xl shadow">
                <div id="loadingIndicator" class="hidden justify-center items-center mt-4">
                    <div role="status">
                        <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin fill-blue-600"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="..." fill="currentColor" />
                            <path d="..." fill="currentFill" />
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-xl overflow-hidde">
                    <thead class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-white uppercase">Nama
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-white uppercase">NRP
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-white uppercase">
                                Prodi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-700 dark:text-white uppercase">
                                Status KP</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody"
                        class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-700">
                        <!-- Isi dengan data mahasiswa -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('feedbackPieChart').getContext('2d');
        const feedbackPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Sudah Isi Feedback', 'Belum Isi Feedback'],
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: [{{ $sudahIsiFeedback }}, {{ $belumIsiFeedback }}],
                    backgroundColor: ['#10B981', '#EF4444'], // hijau & merah
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '{{ auth()->user()->theme == 'dark' ? 'white' : 'black' }}'
                        }
                    }
                }
            }
        });
    </script>
    <script>
        async function fetchDetailMahasiswa(status) {
                document.getElementById('loadingIndicator').classList.remove('hidden');
                document.getElementById('detailSection').classList.remove('hidden');

                const periode = document.getElementById('periodeFilter').value;
                const prodi = document.getElementById('prodiFilter').value;

                try {
                    const res = await fetch(`/dashboard/detail?status=${status}&periode=${periode}&prodi=${prodi}`);
                    const data = await res.json();

                    const tbody = document.getElementById('detailTableBody');
                    tbody.innerHTML = "";

                    data.forEach(mhs => {
                        tbody.innerHTML += `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${mhs.name}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">${mhs.student_number}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">${mhs.prodi}</td>
                    <td class="px-4 py-3 text-sm font-semibold ${
                        mhs.status === 'Finished' ? 'text-green-600' : 'text-red-500'
                    }">${mhs.status}</td>
                </tr>
            `;
                    });

                } catch (error) {
                    alert("Gagal memuat data.");
                } finally {
                    document.getElementById('loadingIndicator').classList.add('hidden');
                }
            }
            ['periodeFilter', 'prodiFilter', 'semesterFilter'].forEach(id => {
                document.getElementById(id).addEventListener('change', () => {
                    fetchDetailMahasiswa('Finished'); // default status atau yang dipilih terakhir
                });
            });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const prodiLabels = @json($prodiLabels);
        const prodiCounts = @json($prodiCounts);
    </script>

    <script>
        const pieChart = new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Selesai KP', 'Belum KP', 'Sedang KP'],
                datasets: [{
                    data: [{{ $sudahKP }}, {{ $belumKP }}, {{ $sedangKP }}],
                    backgroundColor: ['#34D399', '#F87171', '#FFD700'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.raw}`
                        }
                    }
                },
                onClick: (e, chartItem) => {
                    if (chartItem.length > 0) {
                        const label = chartItem[0].element.$context.raw;
                        fetchDetailMahasiswa(label);
                    }
                }
            }
        });

        const prodiCtx = document.getElementById('prodiChart').getContext('2d');
        new Chart(prodiCtx, {
            type: 'bar',
            data: {
                labels: prodiLabels,
                datasets: [{
                    label: 'Jumlah Mahasiswa KP',
                    data: prodiCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        async function fetchDetailMahasiswa(status, label) {
            // Tampilkan loading spinner
            document.getElementById('loadingIndicator').classList.remove('hidden');

            const periode = document.getElementById('periodeFilter').value;
            const prodi = document.getElementById('prodiFilter').value;

            try {
                const res = await fetch(`/dashboard/detail?status=${status}&periode=${periode}&prodi=${prodi}`);
                const data = await res.json();

                // Isi ulang tabel
                const tbody = document.getElementById('dataMahasiswaBody');
                tbody.innerHTML = "";

                data.forEach((mhs, index) => {
                    tbody.innerHTML += `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-3 text-sm">${index + 1}</td>
                    <td class="px-6 py-3 text-sm">${mhs.name}</td>
                    <td class="px-6 py-3 text-sm">${mhs.student_number}</td>
                    <td class="px-6 py-3 text-sm">${mhs.prodi}</td>
                    <td class="px-6 py-3 text-sm">${mhs.status}</td>
                </tr>
            `;
                });

                document.getElementById("chartTitle").textContent = `Data Mahasiswa (${label})`;
            } catch (err) {
                alert("Gagal memuat data mahasiswa");
            } finally {
                // Sembunyikan spinner setelah selesai
                document.getElementById('loadingIndicator').classList.add('hidden');
            }
        }
    </script>
    <script>
        const prodiLabels = @json($perProdi->pluck('prodi'));
        const prodiCounts = @json($perProdi->pluck('total'));
    </script>
@endsection
