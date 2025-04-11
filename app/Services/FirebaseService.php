<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $storage;
    protected $bucket;

    public function __construct()
    {
        $credentialsFile = storage_path(env('FIREBASE_CREDENTIALS'));
        $factory = (new Factory)->withServiceAccount($credentialsFile);
        $this->storage = $factory->createStorage();
        $this->bucket = $this->storage->getBucket();
    }

    public function uploadFile($file, $folder = 'uploads')
    {
        try {
            // Gunakan nama file original dengan prefix random untuk menghindari duplikasi
            $filename = $folder . '/' . Str::random(10) . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Upload file langsung ke Firebase (metode yang sudah ada)
            $this->bucket->upload(
                file_get_contents($file),
                ['name' => $filename]
            );

            // Dapatkan URL yang benar dari Firebase
            $object = $this->bucket->object($filename);

            // Opsi 1: URL dengan signed URL (lebih aman, bisa diatur masa berlakunya)
            // $expiresAt = new \DateTime('+ 10 years');
            // $url = $object->signedUrl($expiresAt);

            // Opsi 2: URL publik - menggunakan format yang sudah ada
            $url = "https://firebasestorage.googleapis.com/v0/b/" . $this->bucket->name() . "/o/" . urlencode($filename) . "?alt=media";

            Log::info('File berhasil diupload ke Firebase: ' . $url);
            return $url;
        } catch (\Exception $e) {
            Log::error('Error saat upload file ke Firebase: ' . $e->getMessage());
            throw $e;
        }
    }

    // Menambahkan fungsi untuk menghapus file (opsional)
    public function deleteFile($fileUrl)
    {
        try {
            // Ekstrak filename dari URL
            $filename = str_replace("https://storage.googleapis.com/" . $this->bucket->name() . "/", "", $fileUrl);

            // Hapus file dari Firebase
            $this->bucket->object($filename)->delete();
            Log::info('File berhasil dihapus dari Firebase: ' . $filename);
            return true;
        } catch (\Exception $e) {
            Log::error('Error saat menghapus file dari Firebase: ' . $e->getMessage());
            return false;
        }
    }
}
