<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class CompanyController extends Controller
{
    // Menampilkan Halaman Profil Perusahaan
    public function companydashboard()
    {
        $company = Company::where('user_id', Auth::id())->first();
        return view('company.companydashboard', compact('company'));
    }

    // Menyimpan atau Memperbarui Data Perusahaan
    public function actionsavecompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20|',
            'description' => 'nullable|string',
            'x_coordinate' => 'nullable|numeric',
            'y_coordinate' => 'nullable|numeric',
        ]);

        $company = Company::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->except('logo')
        );

        // Jika ada logo yang diunggah, simpan ke storage
        if ($request->hasFile('logo')) {
            try {
                $logo = $request->file('logo');

                // Hapus logo lama jika ada
                if ($company->logo) {
                    $oldPath = str_replace(url('/storage'), '', $company->logo);
                    Storage::disk('public')->delete($oldPath);
                }

                // Generate nama file unik
                $fileName = 'company_' . Str::slug($company->name) . '_' . time() . '.' . $logo->getClientOriginalExtension();

                // Simpan file ke storage
                $path = $logo->storeAs('company_logos', $fileName, 'public');

                // Dapatkan URL publik
                $logoUrl = Storage::url($path);

                // Update database
                $company->logo = $logoUrl;
                $company->save();

                return redirect()->back()->with('success', 'Logo perusahaan berhasil diperbarui');
            } catch (\Exception $e) {
                Log::error('Error upload logo: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal mengupload logo: ' . $e->getMessage());
            }
        }

        return redirect()->route('companydashboard')->with('success', 'Profil berhasil diperbarui.');
    }
}
