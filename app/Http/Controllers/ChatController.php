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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('image');
        $localFolder = public_path('firebase-temp-uploads') . '/';
        $extension = $image->getClientOriginalExtension();
        $fileName = time() . '.' . $extension;

        // $tempPath = storage_path('app/temp/' . $fileName);
        $image->move($localFolder, $fileName);

        // Upload ke Firebase
        $uploadedFile = fopen($localFolder . $fileName, 'r');
        app('firebase.storage')->getBucket()->upload($uploadedFile, [
            'name' => 'image/' . $fileName,
        ]);

        // Hapus file lokal
        unlink($localFolder . $fileName);

        // Dapatkan URL untuk disimpan di database
        $fileUrl = "https://firebasestorage.googleapis.com/v0/b/proyekakhir-7f1e1.firebasestorage.app/o/" . urlencode('image/' . $fileName) . "?alt=media";

        $chat = Chat::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'image' => $fileUrl
        ]);

        return response()->json($chat, 201);
    }
}
