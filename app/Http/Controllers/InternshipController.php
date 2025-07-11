<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Students;
use App\Models\User;
use App\Models\Vacancy;
use App\Services\FirebaseService;
use Carbon\Carbon;
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
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

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
        $user = User::where('user_id', Auth::user()->user_id)->firstOrFail();

        // Validasi kelengkapan data
        foreach (['name', 'prodi', 'department', 'student_number', 'cv'] as $field) {
            if (empty($student->$field)) {
                return redirect()->back()->with('error', "Mohon lengkapi data diri terlebih dahulu: {$field} di bagian data diri belum diisi.");
            }
        }

        if (empty($user->email)) {
            return redirect()->back()->with('error', "Mohon lengkapi data diri terlebih dahulu: Email belum diisi.");
        }

        // Cek apakah sudah pernah apply ke lowongan ini
        $existing = DB::table('applications')
            ->where('student_id', $student->student_id)
            ->where('vacancy_id', $id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah pernah mengajukan lamaran untuk lowongan ini.');
        }

        // Validasi file
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:2048',
        ]);

        try {
            $document = $request->file('document');
            $path = $document->store('cover_letter', 'public');
            $fileUrl = Storage::url($path);

            DB::table('applications')->insert([
                'user_id' => Auth::user()->user_id,
                'student_id' => $student->student_id,
                'vacancy_id' => $id,
                'application_date' => now(),
                'document' => $fileUrl,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');
        } catch (\Exception $e) {
            Log::error('Error saat apply internship: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim pengajuan: ' . $e->getMessage());
        }
    }

    private function generatePeriode(Carbon $startDate)
    {
        $tahun = $startDate->year;
        // Jika bulan < Juli, berarti masih tahun ajaran sebelumnya
        if ($startDate->month < 7) {
            $tahun = ($tahun - 1) . '/' . $tahun;

            // dd($tahun);
            return  $tahun;
        } else {
            $tahun = $tahun . '/' . ($tahun + 1);
            // dd($tahun);
            return $tahun;
        }
    }

    private function generateSemester(Carbon $startDate)
    {
        $tahun = $startDate->year;

        // Tentukan minggu ke-3 bulan Februari dan Agustus
        $mingguKetigaFeb = Carbon::createFromDate($tahun, 2, 15)->startOfWeek();
        $mingguKetigaAgu = Carbon::createFromDate($tahun, 8, 15)->startOfWeek();

        //

        if ($startDate->between($mingguKetigaFeb, $mingguKetigaAgu->copy()->subDay())) {
            $semester = 'genap';
            return $semester;
        } else {
            $semester = 'ganjil';
            return $semester;
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $student = Students::where('user_id', Auth::user()->user_id)->firstOrFail();
        $user = User::where('user_id', Auth::user()->user_id)->firstOrFail();

        // Validasi kelengkapan data
        foreach (['name', 'prodi', 'department', 'student_number', 'cv'] as $field) {
            if (empty($student->$field)) {
                return redirect()->back()->with('error', "Mohon lengkapi data diri terlebih dahulu: {$field} di bagian data diri belum diisi.");
            }
        }

        if (empty($user->email)) {
            return redirect()->back()->with('error', "Mohon lengkapi data diri terlebih dahulu: Email belum diisi.");
        }





        // $application = Applications::where('student_id', $student->student_id)
        // ->where('vacancy_id', $request->vacancy_id)
        // ->first();
        // if ($internship) {
        //     if ($internship->status == 'Pending' || $internship->status == 'Approved') {
        //         return redirect()->back()->with('error', 'Lowongan ini sudah ditolak.');
        //     }
        // }
        $request->validate([
            // 'user_id' => 'required|exists:users,id',
            'vacancy_id' => 'required|exists:vacancy,vacancy_id',
            // 'status' => 'required',
            // 'application_date' => 'required',
            'document' => 'nullable|file|mimes:pdf|max:2048',
            'partner_nrp' => 'nullable|string|exists:students,student_number',
        ]);

        // $vacancy = Vacancy::findOrFail($request->vacancy_id);
        // // Hitung semester & periode dari start_date
        // $startDate = Carbon::parse($vacancy->start_date);
        // $periode = $this->generatePeriode($startDate);
        // $semester = $this->generateSemester($startDate);
        $vacancy = Vacancy::findOrFail($request->vacancy_id);
        $startDate = Carbon::parse($vacancy->start_date);
        // dd($startDate);
        $periode = $this->generatePeriode($startDate);

        $semester = $this->generateSemester($startDate);
        // 1. Cek apakah sudah pernah apply ke lowongan yang sama
        $existingSameVacancy = DB::table('applications')
            ->where('student_id', $student->student_id)
            ->where('vacancy_id', $request->vacancy_id)
            ->first();

        if ($existingSameVacancy) {
            return redirect()->back()->with('error', 'Anda sudah pernah mengajukan lamaran untuk lowongan ini.');
        }

        // 2. Cek apakah sudah punya pengajuan aktif (Pending/Approved)
        $activeApplication = DB::table('applications')
            ->where('student_id', $student->student_id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->first();

        if ($activeApplication) {
            return redirect()->back()->with('error', 'Anda hanya dapat memiliki satu pengajuan aktif. Silakan tunggu sampai pengajuan Anda ditolak.');
        }

        // Cek apakah sudah pernah apply
        $alreadyApplied = Applications::where('student_id', $student->id)
            ->whereIn('status', ['Pending', 'Approved'])
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Anda sudah memiliki pengajuan aktif.');
        }
        // Buat group ID
        $groupId = Str::uuid();
        // Cek jika ada partner
        if ($request->partner_nrp) {
            $partner = Students::where('student_number', $request->partner_nrp)->first();

            if (!$partner) {
                return back()->with('error', 'NRP teman tidak ditemukan.');
            }

            // Cek partner juga belum apply
            $partnerApplied = Applications::where('student_id', $partner->student_id)
                ->whereIn('status', ['Pending', 'Approved'])
                ->exists();

            if ($partnerApplied) {
                return back()->with('error', 'Teman Anda sudah memiliki pengajuan aktif.');
            }

            // Jika ada partner, simpan juga
            if ($partner) {
                $partnerId = Students::where('student_id', $partner->student_id)->firstOrFail()->user_id;
                Applications::create([
                    'student_id' => $partner->student_id,
                    'vacancy_id' => $request->vacancy_id,
                    'user_id' => $partnerId,
                    'status' => 'Pending',
                    'group_id' => $groupId,
                    'application_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'periode' => $periode,
                    'semester' => $semester,
                ]);
            }
        }

        // dd($periode, $semester);

        // dd($student->student_id);
        // Simpan pengajuan utama
        Applications::create([
            'student_id' => $student->student_id,
            'vacancy_id' => $request->vacancy_id,
            'user_id' => $user->user_id,
            'status' => 'Pending',
            'group_id' => $groupId,
            'application_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'periode' => $periode,
            'semester' => $semester,
        ]);

                // dd($periode, $semester);



        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('cover_letter', 'public');
            $fileUrl = Storage::url($path);

            DB::table('applications')->where('group_id', $groupId)->update([
                'document' => $fileUrl,
                'updated_at' => now()
            ]);
        }
        // $application = new Applications();
        // $application->user_id = $user->user_id;
        // $application->student_id = $student->student_id;
        // $application->vacancy_id = $request->vacancy_id;
        // // $application->status = $request->status;
        // $application->application_date = now();
        // // dd($request->all());
        // $application->created_at = now();
        // $application->updated_at = now();
        // if ($request->hasFile('document')) {
        //     $file = $request->file('document');
        //     $path = $file->store('cover_letter', 'public');
        //     $application->document = Storage::url($path);
        // }
        // dd($request->all());
        // $application->save();

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');
    }
    public function terminate(Request $request, $id)
    {
        $request->validate([
            'terminated_reason' => 'required|string|max:1000',
        ]);

        $application = Applications::where('student_id', Auth::user()->id)
            ->findorFail($id);

        // Hanya bisa terminate jika status saat ini 'Approved'
        if ($application->status !== 'Approved') {
            return back()->with('error', 'Hanya bisa melaporkan pemecatan dari status Approved.');
        }

        $application->status = 'Terminated';
        $application->terminated_reason = $request->terminated_reason;
        $application->save();

        return back()->with('success', 'Status pengajuan diubah menjadi Diberhentikan.');
    }
    public function update(Request $request, $id)
    {
        $application = Applications::findOrFail($id);
        // dd($request->all());
        // Validasi file baru jika ada
        $request->validate([
            // 'vacancy_id' => 'required|exists:vacancies,id',
            'status' => 'required',
            'application_date' => 'required',
            'document' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        // Update surat lamaran jika diunggah ulang
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $path = $file->store('cover_letter', 'public');
            $application->document = Storage::url($path);
        }

        // $application->vacancy_id = $request->vacancy_id;
        $application->updated_at = now();
        $application->status = $request->status;
        $application->application_date = $request->application_date;
        $application->save();
        // dd($request->all());
        return redirect()->back()->with('success', 'Pengajuan berhasil diperbarui.');
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
                'applications.internship_id',
                'applications.document as document',
                'applications.periode',
                'applications.semester',
                'applications.group_id',
                'applications.tanggal_sidang',
                'vacancy.division',
                'vacancy.duration',
                'vacancy.type',
                'vacancy.requirements',
                'vacancy.company_id',
                'vacancy.division',
                'vacancy.start_date as start_date_vacancy',
                'vacancy.end_date  as end_date_vacancy',
                'companies.name as company_name',
                'companies.address as company_address',
                'companies.logo as company_logo',
                'internships.internship_id as internship_id_useless',
                'internships.kp_book as kp_book',
                'internships.draft_kp_book as draft_kp_book',
                'internships.book_status as book_status',
                'internships.message as message',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.position',
                'internships.title',
                'internships.rating',
            )
            ->get();
        // dd($data);
        $vacancies = DB::table('vacancy')
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
                'companies.contact_email as company_email',
            )
            ->get();
        // dd($data);
        $companies = Company::all();
        return view('history', compact('data', 'companies', 'vacancies'));
    }

    public function deleteHistory($id)
    {
        $applications = Applications::findOrFail($id);
        $applications->delete();

        if (isset($applications->internship_id)) {
            $internships = Internship::findOrFail($applications->internship_id);
            $internships->delete();
        }
        return redirect()->back()->with('success', 'Berhasil Hapus Pengajuan!');
    }

    public function submitHistoryFeedback(Request $request, $id)
    {
        $data = $request->all();
        // dd($data);
        $request->validate([
            'company_id' => 'required|exists:companies,company_id',
            'vacancy_id' => 'nullable|exists:vacancy,vacancy_id',
            'title' => 'required|string|max:255',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date|after_or_equal:start_date',
            // 'position' => 'required|string|max:255',
            'feedback' => 'required|string',
            'kp_book' => 'nullable|file|mimes:pdf', // Validasi file PDF max 10MB
            'draft_kp_book' => 'nullable|file|mimes:pdf',
        ]);
        // dd($request->all());
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $student = Students::where('user_id', $user->user_id)->firstOrFail();

            // Proses file PDF terlebih dahulu
            $fileUrl = null;
            if ($request->hasFile('kp_book')) {
                $file = $request->file('kp_book');
                $fileName = 'kp_' . time() . '_' . Str::random(8) . '.pdf';
                $filePath = $file->store('public');
                $absolutePath = Storage::path($filePath);

                // Proses watermark PDF
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile($absolutePath);
                $logoPath = storage_path('app/public/watermark_PENS.png'); // Pastikan file PNG ada di sini
                $logoWidth = 50; // Sesuaikan lebar logo (dalam mm)
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tplId);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tplId);

                    // Hitung posisi agar logo di tengah halaman
                    $x = ($size['width'] - $logoWidth) / 2;
                    $y = $size['height'] / 2 - 10; // Bisa diatur agar tidak terlalu ke atas/bawah

                    // Tambahkan logo sebagai watermark
                    $pdf->Image($logoPath, $x, $y, $logoWidth);
                }

                // Simpan file dengan watermark
                $publicPath = 'kp_books/' . $fileName;
                $finalFullPath = storage_path('app/public/' . $publicPath);

                // Pastikan direktori ada
                if (!is_dir(dirname($finalFullPath))) {
                    mkdir(dirname($finalFullPath), 0755, true);
                }

                $pdf->Output($finalFullPath, 'F');

                // Dapatkan URL publik
                $fileUrl = Storage::url($publicPath);

                // Hapus file temporary
                Storage::delete($filePath);
            };

            // Proses file PDF terlebih dahulu
            $draftFileUrl = null;
            if ($request->hasFile('draft_kp_book')) {
                $file = $request->file('draft_kp_book');
                $fileName = 'draft_kp_' . time() . '_' . Str::random(8) . '.pdf';
                $filePath = $file->storeAs('draft_kp_book', 'public');
                $absolutePath = Storage::path($filePath);

                // Proses watermark PDF
                $pdf = new Fpdi();
                $pageCount = $pdf->setSourceFile($absolutePath);
                $logoPath = storage_path('app/public/watermark_PENS.png'); // Pastikan file PNG ada di sini
                $logoWidth = 50; // Sesuaikan lebar logo (dalam mm)

                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tplId);

                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tplId);

                    // Hitung posisi agar logo di tengah halaman
                    $x = ($size['width'] - $logoWidth) / 2;
                    $y = $size['height'] / 2 - 10; // Bisa diatur agar tidak terlalu ke atas/bawah

                    // Tambahkan logo sebagai watermark
                    $pdf->Image($logoPath, $x, $y, $logoWidth);
                }

                // Simpan file dengan watermark
                $publicPath = 'draft_kp_books/' . $fileName;
                $finalFullPath = storage_path('app/public/' . $publicPath);

                // Pastikan direktori ada
                if (!is_dir(dirname($finalFullPath))) {
                    mkdir(dirname($finalFullPath), 0755, true);
                }

                $pdf->Output($finalFullPath, 'F');

                // Dapatkan URL publik
                $draftFileUrl = Storage::url($publicPath);

                // Hapus file temporary
                Storage::delete($filePath);
            };
            $vacancy = Vacancy::findOrFail($request->vacancy_id);
            $startDate = Carbon::parse($vacancy->start_date);
            // dd($startDate);
            // $periode = $this->generatePeriode($startDate);

            // $semester = $this->generateSemester($startDate);
            // dd($request->all());
            // Simpan data internship
            $internshipId = DB::table('internships')->insertGetId([
                'student_id' => $student->student_id,
                'company_id' => $request->company_id,
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'position' => $request->position,
                'feedback' => $request->feedback,
                'created_at' => now(),
                'updated_at' => now(),
                'rating' => $request->rating,
                'application_id' => $id,
                'vacancy_id' => $request->vacancy_id,
                'kp_book' => $fileUrl, // Simpan URL file atau null jika tidak ada file
                'draft_kp_book' => $draftFileUrl,
                // 'periode' => $periode,
                // 'semester' => $semester
            ]);


            // PERBAIKAN: Update internships dengan sintaks yang benar


            if (isset($request->internship_id)) {
                // dd($request->internship_id);
                DB::table('internships')
                    ->where('internship_id', $request->internship_id)
                    ->update([
                        'book_status' => 'Pending',
                        'kp_book' => $fileUrl,
                        'draft_kp_book' => $draftFileUrl,

                        // 'periode' => 'bismillah',
                        // 'semester' => $semester
                    ]);

                // $internship = Internship::where('internship_id', $request->internship_id)->first();
                //     dd($startDate, $periode, $internship);

            }
            // $internshipRevision = DB::table('internships')
            //     ->where('internship_id', $request->internship_id)
            //     ->first();
            // // Update book_status jadi pending jika sudah mengirim revisi
            // if ($internshipRevision->book_status === 'Rejected') {
            //     $internshipRevision->book_status = 'Pending';
            //     $internshipRevision->kp_book = $fileUrl;
            //     $internshipRevision->draft_kp_book = $draftFileUrl;
            //     $internshipRevision->updated_at = now();
            //     $internshipRevision->update();
            // }

            // PERBAIKAN: Update applications dengan sintaks yang benar
            $apps = DB::table('applications')
                ->where('application_id', $id)
                ->update([
                    'internship_id' => $internshipId,
                    'updated_at' => now(),
                ]); // Perbaikan sintaks di sini

            // Ambil data internship yang baru dibuat
            $internship = DB::table('internships')
                ->where('internship_id', $internshipId)
                ->first();

            DB::commit();

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Feedback dan Buku KP berhasil disimpan.',
            //     'internship' => $internship,
            //     'apps_updated' => $apps
            // ]);
            return redirect()->back()->with('success', 'Feedback dan Buku KP berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan feedback dan buku KP: ' . $e->getMessage());
        }
    }

    public function internexperience()
    {
        $data = DB::table('internships')
            ->leftJoin('companies', 'internships.company_id', '=', 'companies.company_id')
            ->leftJoin('students', 'internships.student_id', '=', 'students.student_id')
            ->where('internships.book_status', '=', 'Approved')
            ->select(
                'internships.internship_id as internship_id',
                'internships.title',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.company_id',
                'internships.rating',
                'internships.kp_book',
                'internships.draft_kp_book',
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
            // 'prodi' => 'required|in:D3,D4',
            // 'department' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before:now',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            try {
                // Hapus file lama jika ada
                if ($student->profile_picture) {
                    $oldPath = str_replace(url('/storage'), '', $student->profile_picture);
                    Storage::disk('public')->delete($oldPath);
                }

                // Upload file baru ke storage/app/public
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $profilePictureUrl = Storage::url($path);

                $updateData['profile_picture'] = $profilePictureUrl;
                Log::info('Profile picture uploaded to: ' . $profilePictureUrl);
            } catch (\Exception $e) {
                Log::error('Upload error: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengupload foto profil');
            }
        } else {
            Log::info('Tidak ada perubahan data pada tabel students.');
        }

        // Upload CV ke Storage
        if ($request->hasFile('cv')) {
            try {
                // Hapus CV lama jika ada
                if ($student->cv) {
                    $oldPath = str_replace(url('/storage'), '', $student->cv);
                    Storage::disk('public')->delete($oldPath);
                }

                // Upload file baru
                $cvFile = $request->file('cv');

                // Generate nama file unik
                $fileName = time() . '_' . Str::random(10) . '.' . $cvFile->getClientOriginalExtension();

                // Simpan file ke storage
                $path = $cvFile->storeAs('cv', $fileName, 'public');

                // Dapatkan URL publik
                $cvUrl = Storage::url($path);

                // Update database
                $updateData['cv'] = $cvUrl;
                Log::info('CV berhasil diupload: ' . $cvUrl);
            } catch (\Exception $e) {
                Log::error('Error upload CV: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengupload CV: ' . $e->getMessage());
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



    public function uploadKpBook(Request $request, $id)
    {
        $request->validate([
            'kp_book' => 'required|file|mimes:pdf|max:2048'
        ]);

        try {
            $user = Auth::user();
            $student = Students::where('user_id', $user->user_id)->firstOrFail();
            $internship = DB::table('internships')->where('application_id', $id)->firstOrFail();

            // Hapus file lama jika ada
            if ($internship->kp_book) {
                $oldPath = str_replace('/storage/', '', parse_url($internship->kp_book, PHP_URL_PATH));
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('kp_book');
            $fileName = 'kp_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan file sementara
            $tempPath = $file->storeAs('temp', $fileName, 'local');
            $tempFullPath = storage_path('app/' . $tempPath);

            // Proses watermark
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($tempFullPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);

                $pdf->SetFont('Helvetica', 'I', 24);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->SetXY($size['width'] / 2 - 50, $size['height'] / 2);
                $pdf->Cell($size['width'], 10, 'COPYRIGHT - SURYA INFORMATIKA', 0, 0, 'C');
            }

            // Simpan hasil watermark ke storage publik
            $watermarkedPath = 'kp_books/' . $fileName;
            $finalPath = storage_path('app/public/' . $watermarkedPath);
            $pdf->Output($finalPath, 'F');

            // Dapatkan URL publik
            $fileUrl = Storage::url($watermarkedPath);

            // Simpan ke DB
            DB::table('internships')
                ->where('application_id', $id)
                ->update([
                    'kp_book' => $fileUrl,
                    'updated_at' => now()
                ]);

            // Hapus file temp
            Storage::disk('local')->delete($tempPath);

            return redirect()->back()->with('success', 'Buku KP berhasil diunggah dan diberi watermark.');
        } catch (\Exception $e) {
            Log::error('Gagal upload KP Book: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah: ' . $e->getMessage());
        }
    }
}
