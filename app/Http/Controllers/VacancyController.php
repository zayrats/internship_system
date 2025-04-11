<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function companyinternship()
    {
        $company = Company::where('user_id', Auth::id())->first();
        $vacancies = Vacancy::where('company_id', $company->company_id)
            // ->withCount('applications')
            ->latest()
            ->get();

        return view('company.vacancy', compact('vacancies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:8',
            'type' => 'required|in:WHO,WFH',
            'requirements' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $vacancy = Vacancy::updateOrCreate(
            ['id' => $request->id, 'company_id' => Auth::id()],
            $request->all()
        );

        return redirect()->route('vacancy.index')->with('success', 'Lowongan berhasil disimpan.');
    }

    public function destroy($id)
    {
        $company = Company::where('user_id', Auth::id())->first();
        $vacancy = Vacancy::where('company_id', $company->company_id)->findOrFail($id);

        if ($vacancy->applications()->count() > 0) {
            return redirect()->back()->with('error', 'Lowongan tidak bisa dihapus karena sudah ada pengajuan.');    
        }
        // DB::table('vacancy')->where('id', $id)->delete();
        // $vacancy->delete();
        return redirect()->route('vacancy.index')->with('success', 'Lowongan berhasil dihapus.');
    }
    public function monitor()
    {
        $company = Company::where('user_id', Auth::id())->first();

        // Pastikan perusahaan ditemukan sebelum lanjut
        if (!$company) {
            return redirect()->back()->with('error', 'Perusahaan tidak ditemukan.');
        }

        $applications = Applications::whereHas('vacancy', function ($query) use ($company) {
            $query->where('company_id', $company->company_id);
        })->whereIn('status', ['Approved', 'Finished'])
            ->with(['student', 'vacancy'])
            ->get();
            // dd($applications);
        return view('company.monitor', compact('applications'));
    }



}
