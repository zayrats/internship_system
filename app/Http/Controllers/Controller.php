<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Company;
use App\Models\Internship;
use Faker\Core\Coordinates;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

use function Laravel\Prompts\select;

class Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user && in_array($user->role, ['Dosen'])) {
            return view('dosen.dashboard');
        }

        if ($user && in_array($user->role, ['Pustakawan'])) {
            return view('pustakawan');
        }
        if ($user && in_array($user->role, ['Admin'])) {
            return view('admin.admindashboard');
        }

        // Ambil semua perusahaan yang punya koordinat
        $companies = Company::with(['internships.student'])
            ->whereNotNull('x_coordinate')
            ->whereNotNull('y_coordinate')
            ->get();

        $initialMarkers = [];

        foreach ($companies as $company) {
            $students = [];

            foreach ($company->internships as $internship) {
                if ($internship->student) {
                    $fullName = $internship->student->name;
                    $nameParts = explode(' ', $fullName);

                    if (count($nameParts) >= 3) {
                        $middleIndex = floor(count($nameParts) / 2);
                        $highlighted = $nameParts;
                        $highlighted[$middleIndex] = '<strong>' . $highlighted[$middleIndex] . '</strong>';
                        $displayName = implode(' ', $highlighted);
                    } else {
                        $displayName = $fullName;
                    }

                    $students[] = $displayName;
                }
            }


            $initialMarkers[] = [
                'position' => [
                    'lat' => (float) $company->x_coordinate,
                    'lng' => (float) $company->y_coordinate,
                ],
                'draggable' => false,
                'name' => $company->name,
                'total_intern' => count($students),
                'students' => $students
            ];
        }

        return view('homepage', compact('initialMarkers'));
    }


    public function history(Request $request)
    {
        return view('history');
    }
    public function maps()
    {
        // Ambil semua data internship beserta relasi ke company
        $internships = Internship::with('company')->get();

        // Inisialisasi array markers
        $initialMarkers = [];

        // Loop tiap internship dan ambil koordinat perusahaannya
        foreach ($internships as $internship) {
            if ($internship->company && $internship->company->x_coordinate && $internship->company->y_coordinate) {
                $initialMarkers[] = [
                    'position' => [
                        'lat' => (float) $internship->company->x_coordinate,
                        'lng' => (float) $internship->company->y_coordinate,
                    ],
                    'draggable' => false,
                    'name' => $internship->company->name
                ];
            }
        }

        return view('maps', compact('initialMarkers'));
    }

    public function profile(Request $request)
    {
        return view('profile');
    }
    public function internexperience(Request $request)
    {
        return view('internexperience');
    }
    public function studentdashboard(Request $request)
    {
        return view('studentdashboard');
    }
}

//login



//Student section
