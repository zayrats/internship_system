<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Chat;


class ChatController
{
    // Mengambil semua chat terbaru
    public function index()
    {
        return response()->json(
            Chat::with('user:user_id,username')->latest()->take(50)->get()
        );
    }

    // Menyimpan pesan baru
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json($chat, 201);
    }
}
