<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\AnswerComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QnAController
{
    // Menampilkan semua pertanyaan
    public function index()
    {
        // $user = User::find(Auth::id());
        $questions = Question::with('user:user_id,username')->latest()->take(20)->paginate(10);
        // dd($questions);
        return view('qna', compact('questions'));
    }

    // Form buat pertanyaan
    public function create()
    {
        return view('qnacreate');
    }

    // Simpan pertanyaan baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Question::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('qna.index')->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function storeComment(Request $request, $answerId)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        AnswerComment::create([
            'user_id' => Auth::id(),
            'answer_id' => $answerId,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }
    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $parent = AnswerComment::findOrFail($commentId);

        AnswerComment::create([
            'answer_id' => $parent->answer_id,
            'user_id' => Auth::user()->id,
            'comment' => $request->comment,
            'parent_id' => $parent->id,
        ]);

        return back();
    }

    // Detail pertanyaan & jawabannya
    public function show(Question $question)
    {
        $question->load(['user:user_id,username', 'answers.user']);
        // dd($question);
        return view('qnashow', compact('question'));
    }

    // Simpan jawaban
    public function answer(Request $request, Question $question)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        // dd($request->all());
        Answer::create([
            'question_id' => $question->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Jawaban berhasil dikirim!');
    }
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $questions = Question::with('user')
            ->where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('content', 'LIKE', "%{$keyword}%")
            ->latest()
            ->paginate(10);

        return view('qna', compact('questions', 'keyword'));
    }
}
