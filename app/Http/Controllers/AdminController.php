<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController
{


    public function admindashboard(Request $request) {}

    public function monitoringMahasiswa(Request $request)
    {
        $query = DB::table('internships')
            ->join('students', 'internships.student_id', '=', 'students.student_id')
            ->join('companies', 'internships.company_id', '=', 'companies.company_id')
            ->select('students.name', 'students.student_number', 'students.prodi', 'companies.name as company_name', 'internships.start_date', 'internships.end_date');

        // Filter berdasarkan perusahaan
        if (request()->has('company_id') && $request->company_id != '') {
            $query->where('companies.company_id', $request->company_id);
        }

        // Filter berdasarkan status
        if (request()->has('status') && $request->status != '') {
            $now = now();
            if ($request->status == 'sedang') {
                $query->whereDate('internships.start_date', '<=', $now)->whereDate('internships.end_date', '>=', $now);
            } elseif ($request->status == 'belum') {
                $query->whereDate('internships.start_date', '>', $now);
            } elseif ($request->status == 'selesai') {
                $query->whereDate('internships.end_date', '<', $now);
            }
        }

        // Pencarian mahasiswa
        if (request()->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('students.name', 'like', "%{$request->search}%")
                    ->orWhere('students.nrp', 'like', "%{$request->search}%");
            });
        }

        $internships = $query->paginate(10);
        $companies = DB::table('companies')->get();

        return view('admin.monitoringMahasiswa', compact('internships', 'companies'));
    }

    public function manageJobs(Request $request)
    {
        $query = DB::table('vacancy')
            ->join('companies', 'vacancy.company_id', '=', 'companies.company_id')
            ->select('vacancy.*', 'companies.name as company_name');

        // Filter berdasarkan perusahaan
        if ($request->filled('company_id')) {
            $query->where('companies.company_id', $request->company_id);
        }

        // Filter status lowongan
        if ($request->filled('status')) {
            $now = now();
            if ($request->status == 'aktif') {
                $query->whereDate('vacancy.end_date', '>=', $now);
            } elseif ($request->status == 'berakhir') {
                $query->whereDate('vacancy.end_date', '<', $now);
            }
        }

        // Pencarian berdasarkan posisi
        if ($request->filled('search')) {
            $query->where('vacancy.division', 'like', "%{$request->search}%");
        }

        $jobs = Vacancy::with('company')->paginate(10);
        $companies = DB::table('companies')->get();
        // dd($companies, $jobs);
        return view('admin.jobs', compact('jobs', 'companies'));
    }
    public function updateJob(Request $request, $id)
    {
        $request->validate([
            'approval' => 'required|in:Approved,Rejected',
        ]);
        // dd($request->all());
        DB::table('vacancy')
            ->where('vacancy_id', $id)
            ->update(['approval' => $request->approval]);


        return redirect()->route('admin.jobs')->with('success', 'Status lowongan berhasil diperbarui');
    }

    public function deleteJob($id)
    {
        DB::table('vacancy')->where('id', $id)->delete();
        return redirect()->route('admin.jobs')->with('success', 'Lowongan berhasil dihapus');
    }

    public function rekapInternships(Request $request)
    {
        $query = DB::table('internships')
            ->join('students', 'internships.student_id', '=', 'students.id')
            ->join('companies', 'internships.company_id', '=', 'companies.id')
            ->select('internships.*', 'students.name as student', 'students.nrp', 'companies.name as company');

        // Filter berdasarkan perusahaan
        if (request()->has('company_id') && $request->company_id != '') {
            $query->where('companies.id', $request->company_id);
        }

        // Filter status magang
        if (request()->has('status') && $request->status != '') {
            $query->where('internships.status', $request->status);
        }

        // Pencarian berdasarkan nama mahasiswa
        if (request()->has('search') && $request->search != '') {
            $query->where('students.name', 'like', "%{$request->search}%");
        }

        $internships = $query->paginate(10);
        $companies = DB::table('companies')->get();

        return view('admin.internships', compact('internships', 'companies'));
    }

    public function exportInternships()
    {
        $internships = DB::table('internships')
            ->join('students', 'internships.student_id', '=', 'students.id')
            ->join('companies', 'internships.company_id', '=', 'companies.id')
            ->select('students.name as Nama', 'students.nrp as NRP', 'companies.name as Perusahaan', 'internships.start_date as Mulai', 'internships.end_date as Selesai', 'internships.status as Status')
            ->get();

        return Excel::download(new InternshipsExport($internships), 'rekap_magang.xlsx');
    }

    public function statistics()
    {
        // Statistik jumlah mahasiswa magang per perusahaan
        $companyStats = DB::table('internships')
            ->join('companies', 'internships.company_id', '=', 'companies.company_id')
            ->select('companies.name as company_name', DB::raw('COUNT(internships.internship_id) as total'))
            ->groupBy('companies.name')
            ->get();

        // Statistik jumlah status magang (Aktif vs Selesai)
        $statusStats = DB::table('internships')
            ->join('applications', 'internships.application_id', '=', 'applications.application_id')
            ->select(
                'applications.status',
                DB::raw('COUNT(internships.internship_id) as total')
            )
            ->whereIn('applications.status', ['on_going', 'finished']) // Hanya hitung yang magang atau selesai
            ->groupBy('applications.status')
            ->get();

        // Statistik jumlah mahasiswa magang per bulan
        $monthlyStats = DB::table('internships')
            ->select(
                DB::raw("DATE_FORMAT(start_date, '%Y-%m') as month"),
                DB::raw('COUNT(internships.internship_id) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get();

        return view('admin.statistics', compact('companyStats', 'statusStats', 'monthlyStats'));
    }



    public function detailuser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        return view('detailuser', compact('user'));
    }

    public function updateuser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();
        return redirect()->route('admindashboard');
    }

    public function deleteuser(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);
        $user->delete();
        return redirect()->route('admindashboard');
    }
}
