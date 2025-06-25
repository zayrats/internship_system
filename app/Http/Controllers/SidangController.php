<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Company;
use App\Models\Internship;
use App\Models\Students;
use App\Models\User;
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

class SidangController
{
    public function index(Request $request)
    {
        $query = Applications::with(['student', 'vacancy.company'])
            ->where('status', 'Finished');

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        if ($request->filled('prodi')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        $internships = $query->get();
        // dd($internships);
        $periodes = Applications::distinct()->pluck('periode');
        $semesters = Applications::distinct()->pluck('semester');
        $prodis = Students::distinct()->pluck('prodi');
        return view('dosen.sidang', compact('internships', 'periodes', 'semesters', 'prodis'));
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'tanggal_sidang' => 'required|date',
            ]);
            // dd($request->all());
            Applications::where('application_id', $id)->update([
                'tanggal_sidang' => $request->tanggal_sidang,
            ]);

            return back()->with('success', 'Tanggal sidang berhasil diperbarui.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
