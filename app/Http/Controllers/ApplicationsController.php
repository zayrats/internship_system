<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applications;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ApplicationsController extends Controller
{
    public function internapplications()
    {
        // Ambil data perusahaan yang terkait dengan user yang login
        $company = Company::where('user_id', Auth::id())->first();

        // Pastikan perusahaan ditemukan sebelum lanjut
        if (!$company) {
            return redirect()->back()->with('error', 'Perusahaan tidak ditemukan.');
        }

        // Ambil aplikasi magang berdasarkan company_id
        $applications = Applications::whereHas('vacancy', function ($query) use ($company) {
            $query->where('company_id', $company->company_id);
        })->wherein('status', ['Pending', 'Approved', 'Rejected'])
        ->with(['student', 'vacancy'])->get();
        // dd($applications);
        return view('company.applications', compact('applications'));
    }


    public function approve($id)
    {
        // dd($application_id, Auth::id());
        $company = Company::where('user_id', Auth::id())->first();
        try {
            $application = Applications::whereHas('vacancy', function ($query) use ($company) {
                $query->where('company_id', $company->company_id);
            })->findOrFail($id);
            $application->update(['status' => 'Approved']);

            return back()->with('success', 'Mahasiswa diterima.');

        } catch (\Exception $e) {
            return Response::json(['eror' => $e->getMessage()], 404);
        }
        // $application = Applications::whereHas('vacancy', function ($query) use ($company) {
        //     $query->where('company_id', $company->company_id);
        // })->findOrFail($application_id);

        // $application->update(['status' => 'Approved']);
        // return redirect()->back()->with('success', 'Mahasiswa diterima.');
    }

    public function reject(Request $request, $id)
    {
        $company = Company::where('user_id', Auth::id())->first();
        $request->validate([
            'application_id' => 'required|exists:applications,application_id',
            'rejection_reason' => 'string|max:255',
        ]);

        $application = Applications::whereHas('vacancy', function ($query) use ($company) {
            $query->where('company_id', $company->company_id);
        })->findOrFail($id);
        $application->update([
            'status' => 'Rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('success', 'Mahasiswa ditolak.');
    }
}
