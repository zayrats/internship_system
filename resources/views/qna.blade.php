@extends('master')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Forum Tanya Jawab</h1>
        <a href="{{ route('qna.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">+ Buat Pertanyaan</a>
    </div>

    {{-- Form Pencarian --}}
    <form action="{{ route('questions.search') }}" method="GET" class="flex items-center gap-2 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik..."
               class="p-2 rounded-md border border-gray-300 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 font-black bg-white">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Cari</button>
    </form>

    {{-- Jika ada keyword pencarian --}}
    @if(request()->has('q'))
        <h2 class="text-xl font-semibold mb-4">Hasil pencarian untuk: "{{ request('q') }}"</h2>
    @endif

    {{-- List Pertanyaan --}}
    @forelse ($questions as $question)
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow mb-4 hover:shadow-md transition">
            <a href="{{ route('qna.show', $question) }}" class="text-xl font-semibold text-blue-600 hover:underline">
                {{ $question->title }}
            </a>
            <p class="text-gray-700 dark:text-gray-300 mt-2">{{ Str::limit($question->content, 100) }}</p>
            <div class="text-sm text-gray-500 mt-2">Oleh {{ $question->user->username }} - {{ $question->created_at->diffForHumans() }}</div>
        </div>
    @empty
        <p class="text-gray-600">Tidak ada pertanyaan ditemukan.</p>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-6">{{ $questions->links() }}</div>
</div>
@endsection
