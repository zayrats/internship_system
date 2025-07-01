<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Students;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DosenController
{
    public function dashboard(Request $request)
    {
        $periodeFilter = $request->input('periode');
        $prodiFilter = $request->input('prodi');
        $semesterFilter = $request->input('semester'); // untuk keperluan mendatang

        // Ambil data dropdown unik
        $periodes = Applications::select('periode')->whereNotNull('periode')->distinct()->pluck('periode');
        $prodis = Students::select('prodi')->whereNotNull('prodi')->distinct()->pluck('prodi');
        $semesters = Applications::select('semester')->whereNotNull('semester')->distinct()->pluck('semester');

        // Ambil data aplikasi yang sudah difilter
        $filteredApplications = Applications::with('student')
            ->when($periodeFilter, fn($q) => $q->where('periode', $periodeFilter))
            ->when($prodiFilter, fn($q) => $q->whereHas('student', fn($q2) => $q2->where('prodi', $prodiFilter)))
            ->when($semesterFilter, fn($q) => $q->where('semester', $semesterFilter))
            ->get();

        // Hitung statistik KP
        $sudahKP = $filteredApplications->where('status', 'Finished')->count();
        $belumKP = $filteredApplications->whereNotIn('status', ['Finished', 'Approved'])->count();
        $sedangKP = $filteredApplications->where('status', 'Approved')->count();
        $totalMahasiswa = $filteredApplications->count();

        // Sudah isi feedback: Finished dan punya relasi ke internship
        $sudahIsiFeedback = $filteredApplications
            ->where('status', 'Finished')
            ->whereIn('application_id', Internship::pluck('application_id'))
            ->count();

        // Belum isi feedback: Finished tapi belum punya internship
        $belumIsiFeedback = $filteredApplications
            ->where('status', 'Finished')
            ->whereNotIn('application_id', Internship::pluck('application_id'))
            ->count();

        $totalIsiFeedback = $sudahIsiFeedback + $belumIsiFeedback;

        // Chart per prodi
        $studentsByProdi = $filteredApplications
            ->where('status', 'Finished')
            ->groupBy(fn($app) => $app->student->prodi ?? 'Tidak Diketahui');

        $prodiLabels = $studentsByProdi->keys()->toArray();
        $prodiCounts = $studentsByProdi->map->count()->values()->toArray();

        return view('dosen.dashboard', [
            'periode' => $periodes,
            'prodi' => $prodis,
            'semester' => $semesters,
            'totalMahasiswa' => $totalMahasiswa,
            'sudahKP' => $sudahKP,
            'belumKP' => $belumKP,
            'sedangKP' => $sedangKP,
            'totalIsiFeedback' => $totalIsiFeedback,
            'sudahIsiFeedback' => $sudahIsiFeedback,
            'belumIsiFeedback' => $belumIsiFeedback,
            'prodiLabels' => $prodiLabels,
            'prodiCounts' => $prodiCounts,
            'perProdi' => $studentsByProdi,
        ]);
    }

    public function getMahasiswaByStatus(Request $request)
    {
        $status = $request->input('status');
        $periode = $request->input('periode');
        $prodi = $request->input('prodi');

        $query = Internship::with('student')->where('status', $status);

        if ($periode) {
            $query->where('periode', $periode);
        }
        if ($prodi) {
            $query->whereHas('student', function ($q) use ($prodi) {
                $q->where('prodi', $prodi);
            });
        }

        $result = $query->get()->map(function ($item) {
            return [
                'name' => $item->student->name ?? '-',
                'student_number' => $item->student->student_number ?? '-',
                'prodi' => $item->student->prodi ?? '-',
                'status' => $item->status
            ];
        });

        return response()->json($result);
    }

    public function monitoringMahasiswa(Request $request)
    {
        $query = DB::table('applications') // Perhatikan: apakah nama tabel benar 'applications' atau 'applications'?
            ->join('students', 'applications.student_id', '=', 'students.student_id')
            ->join('vacancy', 'applications.vacancy_id', '=', 'vacancy.vacancy_id')
            ->join('companies', 'vacancy.company_id', '=', 'companies.company_id')
            ->select([
                'applications.*',
                'students.name as name',
                'students.student_number as student_number',
                'students.prodi as prodi',
                'vacancy.start_date',
                'vacancy.end_date',
                'companies.name as company_name'
            ]);

        // Filter berdasarkan perusahaan
        if ($request->filled('company_id')) {
            $query->where('companies.company_id', $request->company_id);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'sedang':
                    $query->whereDate('vacancy.start_date', '<=', $now)
                        ->whereDate('vacancy.end_date', '>=', $now);
                    break;
                case 'belum':
                    $query->whereDate('vacancy.start_date', '>', $now);
                    break;
                case 'selesai':
                    $query->whereDate('vacancy.end_date', '<', $now);
                    break;
            }
        }

        // Pencarian mahasiswa
        if ($request->filled('search')) {
            $searchTerm = "%{$request->search}%";
            $query->where(function ($q) use ($searchTerm) {
                $q->where('students.name', 'like', $searchTerm)
                    ->orWhere('students.student_number', 'like', $searchTerm);
            });
        }

        $internships = $query->paginate(10);
        $companies = DB::table('companies')->get();

        return view('dosen.monitoringmahasiswa', compact('internships', 'companies'));
    }

    public function manageCompanies(Request $request)
    {
        $query = Company::with('vacancies');
        $internships = Vacancy::with(['internships.student'])->get();

        // Filter berdasarkan perusahaan
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter status lowongan
        if ($request->filled('status')) {
            $now = now();
            if ($request->status == 'aktif') {
                $query->whereDate('end_date', '>=', $now);
            } elseif ($request->status == 'tidak aktif') {
                $query->whereDate('end_date', '<', $now);
            }
        }

        // Pencarian berdasarkan posisi
        if ($request->filled('search')) {
            $query->where('division', 'like', "%{$request->search}%");
        }

        $companies = $query->get();
        $vacancies = Vacancy::all();
        // dd($companies->toArray());
        return view('dosen.companies', compact('companies', 'vacancies', 'internships'));
    }
    public function manageJobs(Request $request)
    {
        $query = Vacancy::with('company');

        // Filter berdasarkan perusahaan
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Filter status lowongan
        if ($request->filled('status')) {
            $now = now();
            if ($request->status == 'aktif') {
                $query->whereDate('end_date', '>=', $now);
            } elseif ($request->status == 'berakhir') {
                $query->whereDate('end_date', '<', $now);
            }
        }

        // Pencarian berdasarkan posisi
        if ($request->filled('search')) {
            $query->where('division', 'like', "%{$request->search}%");
        }

        $jobs = $query->paginate(10);
        $companies = Company::all();
        // dd($jobs->toArray());
        return view('dosen.jobs', compact('jobs', 'companies'));
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


        return redirect()->route('dosen.jobs')->with('success', 'Status lowongan berhasil diperbarui');
    }

    public function deleteJob($id)
    {
        DB::table('vacancy')->where('id', $id)->delete();
        return redirect()->route('dosen.jobs')->with('success', 'Lowongan berhasil dihapus');
    }

    public function updateCompany(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'required',
            'x_coordinate' => 'required',
            'y_coordinate' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
        // dd($request->all());
        // Update semua field kecuali logo
        $company->name = $request->name;
        $company->description = $request->description;
        $company->contact_email = $request->email;
        $company->contact_phone = $request->phone;
        $company->address = $request->address;
        $company->x_coordinate = $request->x_coordinate;
        $company->y_coordinate = $request->y_coordinate;
        // dd($request->all());
        // Proses logo jika ada
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            // Hapus logo lama jika ada
            if ($company->logo) {
                $oldPath = str_replace('/storage', '', parse_url($company->logo, PHP_URL_PATH));
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan logo baru
            $fileName = 'company_' . Str::slug($company->name) . '_' . time() . '.' . $logo->getClientOriginalExtension();
            $path = $logo->storeAs('company_logos', $fileName, 'public');
            $company->logo = Storage::url($path);
        }

        // Simpan semua perubahan
        $company->save();

        return redirect()->route('dosen.companies')->with('success', 'Perusahaan berhasil diperbarui');
    }


    public function deleteCompany($id)
    {
        DB::table('companies')->where('company_id', $id)->delete();
        return redirect()->route('dosen.companies')->with('success', 'Perusahaan berhasil dihapus');
    }
    public function addCompany(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'x_coordinate' => 'required',
            'y_coordinate' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,svg|max:2048',

        ]);
        // dd($request->all());
        $company = new Company();
        $company->name = $request->name;
        $company->contact_email = $request->email;
        $company->contact_phone = $request->phone;
        $company->address = $request->address;
        $company->description = $request->description;
        $company->x_coordinate = $request->x_coordinate;
        $company->y_coordinate = $request->y_coordinate;
        $company->logo = $request->logo;
        $company->save();
        // try {
        $logo = $request->file('logo');


        // Generate nama file unik
        $fileName = 'company_' . Str::slug($company->name) . '_' . time() . '.' . $logo->getClientOriginalExtension();

        // Simpan file ke storage
        $path = $logo->storeAs('company_logos', $fileName, 'public');

        // Dapatkan URL publik
        $logoUrl = Storage::url($path);

        // Update database
        $company->logo = $logoUrl;
        $company->save();

        // return redirect()->back()->with('success', 'Logo perusahaan berhasil diperbarui');
        // };

        return redirect()->route('dosen.companies')->with('success', 'Perusahaan berhasil ditambahkan');
    }

    public function addVacancy(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'division' => 'required',
            'type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'duration' => 'required',
            'requirements' => 'required',
        ]);
        // dd($request->all());
        $vacancy = new Vacancy();
        $vacancy->company_id = $request->company_id;
        $vacancy->division = $request->division;
        $vacancy->type = $request->type;
        $vacancy->start_date = $request->start_date;
        $vacancy->end_date = $request->end_date;
        $vacancy->duration = $request->duration;
        $vacancy->requirements = $request->requirements;
        $vacancy->save();


        return redirect()->route('dosen.companies')->with('success', 'Perusahaan berhasil ditambahkan');
    }

    public function updateVacancy(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            // 'company_id' => 'required',
            'division' => 'required',
            'type' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            'end_date' => 'required',
            'duration' => 'required',
            'requirements' => 'required',
        ]);
        // dd($request->all());
        DB::table('vacancy')->where('vacancy_id', $id)->update([
            // 'company_id' => $request->company_id,
            'division' => $request->division,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration' => $request->duration,
            'requirements' => $request->requirements,
            'status' => $request->status,
        ]);


        return redirect()->route('dosen.companies')->with('success', 'Perusahaan berhasil diperbarui');
    }

    public function deleteVacancy(Request $request, $id)
    {
        DB::table('vacancy')->where('vacancy_id', $id)->delete();
        return redirect()->route('dosen.companies')->with('success', 'Perusahaan berhasil dihapus');
    }
}
