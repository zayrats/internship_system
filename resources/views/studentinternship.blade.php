@extends('master')
@section('content')
    <div class="block  p-6 bg-white border border-gray-200  shadow-sm  dark:bg-gray-800 dark:border-gray-700 ">


        <h5 class="mb-2 text-2xl font-semibold font-sans tracking-tight text-gray-900 dark:text-white text-center">Wujudkan Magang Impianmu</h5>

        <form class="max-w-md mx-auto">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Cari</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" id="default-search"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Mobile Developer" required />
                <button type="submit"
                    class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cari</button>
            </div>
        </form>

    </div>
    @if (session('success'))
        <div class="bg-green-500 text-white p-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div
        class=" min-h-screen p-1  bg-gray-100 dark:bg-gray-700 flex flex-col   border border-gray-200  shadow-sm md:flex-row  dark:border-gray-700  ">
        <div class="container mx-auto ">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($internships as $intern)
                    <div
                        class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 p-5">
                        <img class="rounded-t-lg h-32 w-full object-cover" src="{{ $intern->company_logo }}"
                            alt="Company Logo">
                        <h5 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $intern->company_name }}</h5>
                        <p class="text-gray-500 text-sm">{{ $intern->company_address }}</p>
                        <p class="text-base text-gray-700 dark:text-gray-300 mt-2 font-semibold">{{ $intern->division }} -
                            {{ $intern->type }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Duration: {{ $intern->duration }} Month</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Requirements: {{ $intern->requirements }}
                        </p>
                        <div class="flex justify-between items-center mt-4">
                            <button onclick="openModal('{{ $intern->vacancy_id }}')"
                                class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700">Daftar</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>



        <div id="applyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Upload Your CV</h3>
                <form action="{{ route('internshipapply', $intern->vacancy_id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="document" class="w-full border rounded p-2 mb-4" required>
                    <button type="submit" class="w-full bg-blue-700 text-white rounded-lg px-4 py-2">Submit</button>
                </form>
                <button onclick="closeModal()" class="mt-4 text-red-500">Cancel</button>
            </div>
        </div>





    </div>

    <script>
        function openModal(company) {
            document.getElementById('applyModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('applyModal').classList.add('hidden');
        }
    </script>
@endsection
