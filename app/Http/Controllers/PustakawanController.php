<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Students;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Console\View\Components\Success;
use Illuminate\Http\Client\Factory;
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
use Illuminate\Http\Request;

class PustakawanController extends Controller
{
    public function pustakawan()
    {
        $data = DB::table('internships')
            ->leftJoin('students', 'internships.student_id', '=', 'students.student_id')
            ->leftJoin('companies', 'internships.company_id', '=', 'companies.company_id')
            ->select(
                'internships.internship_id as id',
                'internships.title',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.company_id',
                'internships.rating',
                'internships.kp_book',
                'internships.draft_kp_book',
                'internships.book_status',
                'internships.message',
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

        return view('pustakawan', compact('data'));
    }

    public function before()
    {
        $data = DB::table('internships')
            ->leftJoin('students', 'internships.student_id', '=', 'students.student_id')
            ->leftJoin('companies', 'internships.company_id', '=', 'companies.company_id')
            ->where('internships.book_status', '=', 'Pending')
            ->select(
                'internships.internship_id as id',
                'internships.title',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.company_id',
                'internships.rating',
                'internships.kp_book',
                'internships.draft_kp_book',
                'internships.book_status',
                'internships.message',
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

        return view('before', compact('data'));
    }

    public function after()
    {
        $data = DB::table('internships')
            ->leftJoin('students', 'internships.student_id', '=', 'students.student_id')
            ->leftJoin('companies', 'internships.company_id', '=', 'companies.company_id')
            ->whereIn('internships.book_status', ['Declined', 'Rejected'])
            ->select(
                'internships.internship_id as id',
                'internships.title',
                'internships.feedback',
                'internships.start_date',
                'internships.end_date',
                'internships.company_id',
                'internships.rating',
                'internships.kp_book',
                'internships.draft_kp_book',
                'internships.book_status',
                'internships.message',
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

        return view('after', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $internship = Internship::findOrFail($id);

        $request->validate([
            // 'title' => 'required|string|max:255',
            // 'start_date' => 'required|date',
            // 'end_date' => 'required|date|after_or_equal:start_date',
            // 'position' => 'required|string|max:255',
            // 'feedback' => 'required|string',
            // 'kp_book' => 'nullable|file|mimes:pdf|max:10240', // Validasi file PDF max 10MB
            // 'draft_kp_book' => 'nullable|file|mimes:pdf|max:10240',
            'book_status' => 'required',
            'message' => 'nullable|string|max:255',
        ]);

        // $internship->title = $request->title;
        // $internship->start_date = $request->start_date;
        // $internship->end_date = $request->end_date;
        // $internship->position = $request->position;
        // $internship->feedback = $request->feedback;
        // $internship->kp_book = $request->kp_book;
        $internship->book_status = $request->book_status;
        $internship->message = $request->message;
        $internship->save();

        return redirect()->back()->with('success', 'Berhasil memperbarui data pustakawan.');
    }
}
