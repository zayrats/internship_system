<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Students;
use App\Services\FirebaseService;
use Illuminate\Console\View\Components\Success;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Role as JetstreamRole;
use Laravel\Jetstream\Rules\Role;
use Illuminate\Support\Facades\Hash;

use function Symfony\Component\Clock\now;

class InternshipController
{
    public function internship()
    {
        $internships = DB::table('vacancy')
            ->leftJoin('companies', 'vacancy.company_id', '=', 'companies.company_id')
            // ->where('vacancy.company_id', '=', 'companies.company_id')
            ->select(
                'vacancy.vacancy_id as vacancy_id',
                'vacancy.division',
                'vacancy.duration',
                'vacancy.type',
                'vacancy.requirements',
                'vacancy.start_date',
                'vacancy.end_date',
                'companies.name as company_name',
                'companies.address as company_address',
                'companies.logo as company_logo',
                'companies.contact_email as company_email'
            )
            ->get();

        $user = Auth::user();
        $student = Students::where('user_id', $user->user_id)->firstOrFail();

        return view('studentinternship', compact('internships', 'student'));
    }

    public function internshipapply(Request $request, $id)
    {
        $student = Students::where('user_id', Auth::user()->user_id)->firstOrFail();
        // Validasi file
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:2048',
        ]);

        try {
            $document = $request->file('document');
            $localFolder = public_path('firebase-temp-uploads') . '/';
            $extension = $document->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $document->move($localFolder, $fileName);

            $uploadedFile = fopen($localFolder . $fileName, 'r');

            // Upload ke Firebase Storage
            app('firebase.storage')->getBucket()->upload($uploadedFile, [
                'name' => 'documents/' . $fileName,
            ]);

            unlink($localFolder . $fileName);

            // Dapatkan URL publik
            $fileUrl = "https://firebasestorage.googleapis.com/v0/b/proyekakhir-7f1e1.firebasestorage.app/o/" . urlencode('documents/' . $fileName) . "?alt=media";

            // Simpan data ke database
            DB::table('applications')->insert([
                'user_id' => Auth::user()->user_id,
                'student_id' => $student->student_id,
                'vacancy_id' => $id,
                'application_date' => now(),
                'document' => $fileUrl, // Simpan URL Firebase
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Application submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error saat apply internship: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $data = DB::table('applications')
            ->leftJoin('vacancy', 'applications.vacancy_id', '=', 'vacancy.vacancy_id')
            ->leftJoin('companies', 'vacancy.company_id', '=', 'companies.company_id')
            ->leftJoin('internships', 'applications.internship_id', '=', 'internships.internship_id')
            ->where('applications.user_id', Auth::id()) // Hanya mengambil data user yang login
            ->select(
                'applications.application_id as id',
                'applications.user_id',
                'applications.vacancy_id',
                'applications.application_date',
                'applications.status as status',
                'vacancy.division',
                'vacancy.duration',
                'vacancy.type',
                'vacancy.requirements',
                'vacancy.company_id',
                'companies.name as company_name',
                'companies.address as company_address',
                'companies.logo as company_logo',
                'internships.internship_id',
                'internships.kp_book',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.position',
                'internships.title',
                'internships.rating'
            )
            ->get();
            // dd($data);
        $companies = Company::all();
        return view('history', compact('data', 'companies'));
    }

    public function deleteHistory($id)
    {
        $applications = Applications::findOrFail($id);
        $applications->delete();

        return redirect()->back()->with('success', 'Berhasil Hapus Pengajuan!');
    }

    public function submitHistoryFeedback(Request $request, $id)
    {
        $user = Auth::user();
        $student = Students::where('user_id', $user->user_id)->firstOrFail();
        $request->validate([
            'company_id' => 'required|exists:companies,company_id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'position' => 'required|string|max:255',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        Internship::create([
            'student_id' => $student->student_id,
            'company_id' => $request->company_id,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'position' => $request->position,
            'feedback' => $request->feedback,
            'rating' => $request->rating,
            'application_id' => $id
        ]);

        Applications::update([
            'internship_id' => $id,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Berhasil kirimkan rating!');
    }

    public function internexperience()
    {
        $data = DB::table('internships')
            ->leftJoin('companies', 'internships.company_id', '=', 'companies.company_id')
            ->leftJoin('students', 'internships.student_id', '=', 'students.student_id')
            ->select(
                'internships.internship_id as internship_id',
                'internships.title',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.company_id',
                'internships.rating',
                'internships.kp_book',
                'companies.name as company_name',
                'companies.address as company_address',
                'companies.logo as company_logo',
                'internships.student_id',
                'students.name as student_name',
                'students.department as student_department',
                'students.year as student_year',
                'internships.position',
                DB::raw('TIMESTAMPDIFF(MONTH, internships.start_date, internships.end_date) as duration')
            )
            ->get();

        return view('internexperience', compact('data'));
    }
    public function profile()
    {
        $user = Auth::user();
        $student = Students::where('user_id', $user->user_id)->firstOrFail();
        $internships = Internship::where('student_id', $student->id)->get();
        // dd($student);
        return view('profile', compact('student', 'internships', 'user'));
    }

    public function updateProfile(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        $student = Students::where('user_id', $user->user_id)->firstOrFail();
        // dd($student, $user->user_id);
        $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|min:8',
            'prodi' => 'required|in:D3,D4',
            'department' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv' => 'nullable|mimes:pdf|max:2048',
        ]);
        // dd($request ->all());
        // try {
        DB::beginTransaction(); // Mulai transaksi

        // Update email di tabel users
        if ($request->email !== $user->email) {
            $updateEmail = $user->update(['email' => $request->email]);
            Log::info('Update email: ' . ($updateEmail ? 'BERHASIL' : 'GAGAL'));
            if (!$updateEmail) {
                throw new \Exception('Gagal memperbarui email.');
            }
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $updatePassword = $user->update(['password' => Hash::make($request->password)]);
            Log::info('Update password: ' . ($updatePassword ? 'BERHASIL' : 'GAGAL'));
            if (!$updatePassword) {
                throw new \Exception('Gagal memperbarui password.');
            }
        }
        // dd($request->all());
        // Data yang akan diupdate di tabel students
        $updateData = $request->only([
            'name',
            'student_number',
            'prodi',
            'department',
            'year',
            'phone',
            'address',
            'instagram',
            'birthdate',
            'gender'
        ]);

        // Upload Foto Profil (Jika ada)
        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture) {
                app('firebase.storage')->getBucket()->object('profile_picture/' . $student->profile_picture)->delete();
            }
            $profilePictureFile = $request->file('profile_picture');
            $localFolder = public_path('firebase-temp-uploads') . '/';
            $extension = $profilePictureFile->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;

            if (!file_exists($localFolder)) {
                mkdir($localFolder, 0777, true);
            }

            if ($profilePictureFile->move($localFolder, $fileName)) {
                $uploadedFile = fopen($localFolder . $fileName, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedFile, [
                    'name' => 'profile_picture/' . $fileName
                ]);
                unlink($localFolder . $fileName);
                $photoUrl = "https://firebasestorage.googleapis.com/v0/b/proyekakhir-7f1e1.firebasestorage.app/o/" . urlencode('profile_picture/' . $fileName) . "?alt=media";
                $updateData['profile_picture'] = $photoUrl;

                Log::info('Profile picture uploaded to Firebase Storage' . $photoUrl);
            }else{
                throw new \Exception('Gagal memindahkan file CV ke folder sementara.');
            }
        }

        // Upload CV ke Firebase Storage
        if ($request->hasFile('cv')) {
            // Simpan file ke folder sementara
            $cvFile = $request->file('cv');
            $localFolder = public_path('firebase-temp-uploads') . '/';
            $extension = $cvFile->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;

            // Pastikan folder sementara ada
            if (!file_exists($localFolder)) {
                mkdir($localFolder, 0777, true);
            }

            if ($cvFile->move($localFolder, $fileName)) {
                // Buka file untuk upload
                $uploadedFile = fopen($localFolder . $fileName, 'r');

                // Upload ke Firebase Storage
                app('firebase.storage')->getBucket()->upload($uploadedFile, [
                    'name' => 'cv/' . $fileName
                ]);

                // Hapus file sementara
                unlink($localFolder . $fileName);

                // Dapatkan URL untuk disimpan di database
                $cvUrl = "https://firebasestorage.googleapis.com/v0/b/proyekakhir-7f1e1.firebasestorage.app/o/" . urlencode('cv/' . $fileName) . "?alt=media";
                $updateData['cv'] = $cvUrl;

                // Log berhasil
                Log::info('Berhasil upload CV ke Firebase: ' . $cvUrl);
            } else {
                throw new \Exception('Gagal memindahkan file CV ke folder sementara.');
            }
        }

        // Periksa apakah ada perubahan data
        if (!empty($updateData)) {
            $updateStudent = $student->update($updateData);
            Log::info('Update student: ' . ($updateStudent ? 'BERHASIL' : 'GAGAL'));
            if (!$updateStudent) {
                throw new \Exception('Gagal memperbarui profil mahasiswa.');
            }
        } else {
            Log::info('Tidak ada perubahan data pada tabel students.');
        }

        DB::commit(); // Simpan transaksi jika semua berhasil


        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
        // } catch (\Exception $e) {
        //     DB::rollBack(); // Batalkan perubahan jika terjadi error
        //     Log::error('Kesalahan update profile: ' . $e->getMessage());
        //     return redirect()->route('profile')->with('error', 'Terjadi kesalahan! Silakan coba lagi.');
        // }
    }

    public function submitInternshipFeedback(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,company_id',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'position' => 'required|string|max:255',
            'feedback' => 'required|string'
        ]);

        Internship::create([
            'student_id' => Auth::user()->id,
            'company_id' => $request->company_id,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'position' => $request->position,
            'feedback' => $request->feedback
        ]);

        return response()->json(['message' => 'Feedback magang berhasil disimpan']);
    }

    public function uploadKPBook(Request $request, $id)
    {
        $request->validate([
            'kp_book' => 'required|mimes:pdf|max:2048'
        ]);

        $user = Auth::user();
        $student = Students::where('user_id', $user->user_id)->firstOrFail();
        if (!$student) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        try {
            $kp_book = $request->file('kp_book');
            $localFolder = public_path('firebase-temp-uploads') . '/';
            $extension = $kp_book->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $kp_book->move($localFolder, $fileName);
            $uploadedFile = fopen($localFolder . $fileName, 'r');

            // Upload file ke Firebase Storage


            app('firebase.storage')->getBucket()->upload($uploadedFile, [
                'name' => 'kp_books/' . $fileName,
            ]);

            unlink($localFolder . $fileName);

            // Dapatkan URL untuk disimpan di database
            $fileUrl = "https://firebasestorage.googleapis.com/v0/b/proyekakhir-7f1e1.firebasestorage.app/o/" . urlencode('kp_books/' . $fileName) . "?alt=media";

            // Update database dengan URL Firebase
            DB::table('internships')->where('application_id', $id)->update(['kp_book' => $fileUrl]);

            return redirect()->back()->with('success', 'Buku KP berhasil diunggah');
        } catch (\Exception $e) {
            Log::error('Error saat upload buku kp: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload buku kp: ' . $e->getMessage());
        }
    }
}
