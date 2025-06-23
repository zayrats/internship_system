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
                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-4">
                    <input type="hidden" name="answer_id" value="{{ $answer->id }}">
                    <p>{{ $answer->content }}</p>
                    <div class="text-sm text-gray-500 mt-2">Dijawab oleh {{ $answer->user->username }}</div>
                    {{-- Tombol untuk beri komentar ke jawaban langsung --}}
                    <button onclick="toggleReplyForm('answer-{{ $answer->id }}')"
                        class="text-blue-500 text-xs hover:underline mt-2">Komentari jawaban ini</button>
                        @dump($answer)
                    {{-- Balasan dari komentar --}}
                    @forelse ($reply->replies->where('answer_id', $answer->id) as $reply)
                        <div class="mt-2 ml-4 space-y-2">
                            <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->comment }}</p>
                                <div class="text-xs text-gray-500">— {{ $reply->user->username }}</div>
                            </div>
                        </div>
                    @empty
                    @endforelse



                    {{-- Form reply tersembunyi --}}
                    <form action="{{ route('answer.comment', $answer->id) }}" method="POST" class="mt-2 hidden reply-form"
                        id="reply-form-{{ $reply->id }}">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                        <textarea name="comment" rows="2" class="w-full p-2 border rounded dark:bg-gray-600 dark:text-white text-sm"
                            placeholder="Tulis balasan..." required></textarea>
                        <div class="flex gap-2 mt-1">
                            <button type="submit"
                                class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Kirim</button>
                            <button type="button" onclick="hideReplyForm({{ $reply->id }})"
                                class="px-3 py-1 text-sm bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
                        </div>
                    </form>
                    {{-- Komentar-komentar --}}
                    <div class="mt-4 pl-4 border-l-2 border-blue-400 space-y-2">
                        @foreach ($answer->comments->where('parent_id', null) as $comment)
                            <div class="bg-white dark:bg-gray-800 p-2 rounded shadow-sm">
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $comment->comment }}</p>
                                <div class="text-xs text-gray-500 flex justify-between">
                                    — {{ $comment->user->username }}
                                    <button onclick="toggleReplyForm({{ $comment->id }})"
                                        class="text-blue-500 text-xs hover:underline">Balas</button>
                                </div>




                            </div>
                        @endforeach

                        {{-- Komentar baru (utama) --}}
                        {{-- <form action="{{ route('answer.comment', $answer->id) }}" method="POST" class="mt-4">
                            @csrf
                            <textarea name="comment" rows="2" class="w-full p-2 border rounded dark:bg-gray-600 dark:text-white text-sm"
                                placeholder="Tulis komentar..." required></textarea>
                            <button type="submit"
                                class="mt-1 px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Kirim</button>
                        </form> --}}
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada jawaban.</p>
            @endforelse
        </div>

        {{-- Form Kirim Jawaban --}}
        <form action="{{ route('qna.answer', $question) }}" method="POST"
            class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
            @csrf
            <textarea name="content" rows="4" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white"
                placeholder="Tulis jawabanmu..." required></textarea>
            <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kirim
                Jawaban</button>
        </form>
    </div>

    <script>
        function toggleReplyForm(id) {
            document.querySelectorAll('.reply-form').forEach(form => form.classList.remove('hidden'));
            const form = document.getElementById(`reply-form-${id}`);
            if (form) {
                form.classList.remove('hidden');
                form.querySelector('textarea').focus();
            }
        }

        function hideReplyForm(id) {
            const form = document.getElementById(`reply-form-${id}`);
            if (form) {
                form.classList.add('hidden');
                form.reset();
            }
        }

        // Event listener untuk menyembunyikan form reply saat klik di luar
        document.addEventListener('click', function(event) {
            // Jika yang diklik bukan tombol Balas atau elemen dalam form reply
            if (!event.target.closest('.reply-form') && !event.target.matches(
                    'button[onclick*="toggleReplyForm"]')) {
                document.querySelectorAll('.reply-form').forEach(form => {
                    form.classList.add('hidden');
                });
            }
        });
    </script>

@endsection
