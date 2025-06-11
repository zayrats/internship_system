@extends('master')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">
    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow mb-6">
        <h1 class="text-2xl font-bold text-blue-700">{{ $question->title }}</h1>
        <p class="mt-3 text-gray-800 dark:text-gray-300">{{ $question->content }}</p>
        <div class="text-sm text-gray-500 mt-2">Ditanya oleh {{ $question->user->username }}</div>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Jawaban ({{ $question->answers->count() }})</h2>
        @forelse ($question->answers as $answer)
        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-3">
            <p>{{ $answer->content }}</p>
            <div class="text-sm text-gray-500 mt-2">Dijawab oleh {{ $answer->user->username }}</div>
        </div>
        @empty
        <p>Belum ada jawaban.</p>
        @endforelse
    </div>

    <form action="{{ route('qna.answer', $question) }}" method="POST" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
        @csrf
        <textarea name="content" rows="4" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white" placeholder="Tulis jawabanmu..."></textarea>
        <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kirim Jawaban</button>
    </form>
</div>
@endsection
