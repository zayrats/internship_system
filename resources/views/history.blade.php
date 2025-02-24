@extends('master')
@section('content')
    <div class="min-h-screen p-3  bg-gray-100 dark:bg-gray-700">



        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center my-5">Riwayat Pengajuan Magang</h3>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-600 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Perusahaan</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Tanggal Pengajuan</th>
                        <th scope="col" class="px-6 py-3">Pekerjaan</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item->company_name }}
                            </th>
                            <td class="px-6 py-4 items-start text-start">
                                <div
                                    class="mx-auto font-medium text-sm
                                    {{ strtolower($item->status) === 'Pending' ? 'text-yellow-500 dark:text-yellow-600' : '' }}
                                    {{ strtolower($item->status) === 'Approved' ? 'text-green-500 dark:text-green-600' : '' }}
                                    {{ strtolower($item->status) === 'Rejected' ? 'text-red-500 dark:text-red-600' : '' }}">
                                    {{ ucfirst($item->status) }}
                                </div>
                            </td>

                            <td class="px-6 py-4">{{ $item->application_date }}</td>
                            <td class="px-6 py-4">{{ $item->division }}</td>
                            <td class="px-6 py-4 text-center">
                                <a href="#"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline detail-btn"
                                    data-id="{{ $item->company_id }}" data-name="{{ $item->company_name }}"
                                    data-address="{{ $item->company_address }}"
                                    data-logo="{{ asset('storage/' . $item->company_logo) }}"
                                    data-requirements="{{ $item->requirements }}" data-division="{{ $item->division }}"
                                    data-duration="{{ $item->duration }}" data-type="{{ $item->type }}"
                                    data-status="{{ $item->status }}"
                                    data-application_date="{{ $item->application_date }}">
                                    Lihat
                                </a>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Modal -->
            <div id="detailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
                    <h2 class="text-xl font-bold mb-2">Detail Perusahaan</h2>
                    <img id="modalLogo" class="w-24 h-24 object-cover rounded mb-3" alt="Logo Perusahaan">

                    <p><strong>Nama Perusahaan:</strong> <span id="modalName"></span></p>
                    <p><strong>Alamat:</strong> <span id="modalAddress"></span></p>
                    <p><strong>Persyaratan:</strong> <span id="modalRequirements"></span></p>

                    <hr class="my-2">

                    <p><strong>Divisi:</strong> <span id="modalDivision"></span></p>
                    <p><strong>Durasi:</strong> <span id="modalDuration"></span></p>
                    <p><strong>Tipe:</strong> <span id="modalType"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <p><strong>Tanggal Pengajuan:</strong> <span id="modalApplicationDate"></span></p>

                    <button id="closeModal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Tutup</button>
                </div>
            </div>


        </div>


    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailButtons = document.querySelectorAll('.detail-btn');
            const modal = document.getElementById('detailModal');
            const closeModal = document.getElementById('closeModal');

            // Elemen di dalam modal
            const modalName = document.getElementById('modalName');
            const modalAddress = document.getElementById('modalAddress');
            const modalRequirements = document.getElementById('modalRequirements');
            const modalLogo = document.getElementById('modalLogo');
            const modalDivision = document.getElementById('modalDivision');
            const modalDuration = document.getElementById('modalDuration');
            const modalType = document.getElementById('modalType');
            const modalStatus = document.getElementById('modalStatus');
            const modalApplicationDate = document.getElementById('modalApplicationDate');

            detailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    modalName.textContent = this.dataset.name;
                    modalAddress.textContent = this.dataset.address;
                    modalRequirements.textContent = this.dataset.requirements;
                    modalLogo.src = this.dataset.logo;
                    modalDivision.textContent = this.dataset.division;
                    modalDuration.textContent = this.dataset.duration;
                    modalType.textContent = this.dataset.type;
                    modalStatus.textContent = this.dataset.status;
                    modalApplicationDate.textContent = this.dataset.application_date;

                    modal.classList.remove('hidden');
                });
            });

            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });
    </script>
@endsection
