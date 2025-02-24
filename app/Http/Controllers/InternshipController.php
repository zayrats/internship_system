<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Internship;
use App\Models\Students;
use Illuminate\Console\View\Components\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Role as JetstreamRole;
use Laravel\Jetstream\Rules\Role;

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
                'companies.name as company_name',
                'companies.address as company_address',
                'companies.logo as company_logo'
            )
            ->get();
        if (Auth::check( JetstreamRole::Admin )) {
            return redirect()->back();
        } else {
            return view('studentinternship', compact('internships'));
        }
    }
    public function internshipapply(Request $request, $id)
    {
        // Validasi file
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048', // Maks 2MB
        ]);

        // Simpan file ke penyimpanan (misalnya storage/app/documents)
        $path = $request->file('document')->store('documents', 'public');

        // Simpan data ke database
        DB::table('applications')->insert([
            'user_id' => Auth::user()->user_id,
            'vacancy_id' => $id,
            'application_date' => now(),
            'document' => $path, // Simpan path file
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
    public function history()
    {
        $data = DB::table('applications')
            ->leftJoin('vacancy', 'applications.vacancy_id', '=', 'vacancy.vacancy_id')
            ->leftJoin('companies', 'vacancy.company_id', '=', 'companies.company_id')
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
                'companies.logo as company_logo'
            )
            ->get();

        return view('history', compact('data'));
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
        $student = Students::where('user_id', $user->user_id)->first();
        $internships = Internship::where('student_id', $student->student_id)->get();
        $companies = Company::all();

        return view('profile', compact('student', 'internships', 'companies'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'password' => 'nullable|min:6',
            'prodi' => 'required|in:D3,D4',
            'department' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:' . date('Y')
        ]);

        $student = Students::where('user_id', Auth::user()->id)->first();
        $student->name = $request->name;
        $student->student_number = $request->student_number;
        $student->email = $request->email;
        if ($request->filled('password')) {
            $student->password = bcrypt($request->password);
        }
        $student->prodi = $request->prodi;
        $student->department = $request->department;
        $student->year = $request->year;
        $student->save();

        return response()->json(['message' => 'Profil berhasil diperbarui']);
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

    public function uploadKPBook(Request $request)
    {
        $request->validate([
            'kp_book' => 'required|mimes:pdf|max:2048'
        ]);

        $student = Students::where('student_id', Auth::user()->id)->first();

        if ($student->kp_book) {
            Storage::delete($student->kp_book);
        }

        $path = $request->file('kp_book')->store('kp_books');
        $student->kp_book = $path;
        $student->save();

        return response()->json(['message' => 'Buku KP berhasil diunggah']);
    }
}
