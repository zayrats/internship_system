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
                    <p>{{ $answer->content }}</p>
                    <div class="text-sm text-gray-500 mt-2">Dijawab oleh {{ $answer->user->username }}</div>

                    {{-- Komentar-komentar --}}
                    <div class="mt-4 pl-4 border-l-2 border-blue-400 space-y-4">
                        {{-- sementara tampilkan ID --}}
                        <p>ID Komentar: {{ $comment->id }}</p>
                        @foreach ($answer->comments->where('parent_id', null) as $comment)
                            {{-- sementara tampilkan ID part2 --}}
                            <p>ID Komentar: {{ $comment->id }}</p>
                            <div class="bg-white dark:bg-gray-800 p-3 rounded shadow-sm">
                                <p class="text-sm text-gray-800 dark:text-gray-200">{{ $comment->comment }}</p>
                                <div class="text-xs text-gray-500 flex justify-between items-center">
                                    — {{ $comment->user->username }}
                                    <button onclick="toggleReplyForm({{ $comment->id }})"
                                        class="text-blue-500 text-xs hover:underline">Balas</button>
                                </div>

                                {{-- BALASAN --}}
                                <div class="mt-3 ml-4 space-y-2 border-l border-gray-300 pl-3">
                                    @foreach ($answer->comments->where('parent_id', $comment->id) as $reply)
                                        <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->comment }}</p>
                                            <div class="text-xs text-gray-400">— {{ $reply->user->username }} •
                                                {{ $reply->created_at->diffForHumans() }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Form Balas --}}
                                <form action="{{ route('answer.comment', $answer->id) }}" method="POST"
                                    class="mt-2 hidden reply-form" id="reply-form-{{ $comment->id }}">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <textarea name="comment" rows="2" class="w-full p-2 mt-1 border rounded text-sm dark:bg-gray-700 dark:text-white"
                                        placeholder="Tulis balasan..."></textarea>
                                    <button type="submit"
                                        class="mt-1 px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Kirim</button>
                                </form>
                            </div>
                        @endforeach
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
                placeholder="Tulis jawabanmu..."></textarea>
            <button class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kirim Jawaban</button>
        </form>
    </div>

    <script>
        function toggleReplyForm(commentId) {
            document.querySelectorAll('.reply-form').forEach(el => el.classList.add('hidden'));
            document.getElementById(`reply-form-${commentId}`).classList.toggle('hidden');
        }
    </script>
    <script>
        function toggleReplyForm(commentId) {
            document.querySelectorAll('.reply-form').forEach(el => el.classList.add('hidden'));
            const form = document.getElementById(`reply-form-${commentId}`);
            if (form) form.classList.toggle('hidden');
        }
    </script>

@endsection
