<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Internship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


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
            'contact_phone' => 'nullable|string|max:20',
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
            $logo = $request->file('logo');
            $localFolder = public_path('firebase-temp-uploads') . '/';
            $extension = $logo->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            // $logo->move($localFolder, $fileName);

            // Hapus logo lama jika ada
            if ($company->logo) {
                app('firebase.storage')->getBucket()->object('company_logo/' . $company->logo)->delete();
            }

            if($logo->move($localFolder, $fileName)){
                $uploadedFile = fopen($localFolder . $fileName, 'r');
                app('firebase.storage')->getBucket()->upload($uploadedFile, [
                    'name' => 'company_logo/' . $fileName
                ]);
                unlink($localFolder . $fileName);
                $photoUrl = "https://firebasestorage.googleapis.com/v0/b/proyekakhir-7f1e1.firebasestorage.app/o/" . urlencode('company_logo/' . $fileName) . "?alt=media";
                $company->logo = $photoUrl;
                $company->save();
            }
        }

        return redirect()->route('companydashboard')->with('success', 'Profil berhasil diperbarui.');
    }

}
