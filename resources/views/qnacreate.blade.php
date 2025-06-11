@extends('master')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">
    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Buat Pertanyaan Baru</h1>
        <form action="{{ route('qna.store') }}" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Judul Pertanyaan"
                class="w-full mb-4 p-3 border rounded-lg dark:bg-gray-700 dark:text-white" required>

            <textarea name="content" rows="6" placeholder="Tulis detail pertanyaan kamu di sini..."
                class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white" required></textarea>

            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kirim Pertanyaan</button>
        </form>
    </div>
</div>
@endsection
