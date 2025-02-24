<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

use function Laravel\Prompts\select;


class Controller
{
    public function index(Request $request)
    {
        return view('homepage');
    }

    public function history(Request $request)
    {
        return view('history');
    }
    public function maps()
    {
        $initialMarkers = [
            [
                'position' => [
                    'lat' => -7.8052485,
                    'lng' => 110.3642824
                ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => -7.7925927,
                    'lng' => 110.3658812
                ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => -7.283026,
                    'lng' => 112.750282
                ],
                'draggable' => false
            ],
            [
                'position' => [
                    'lat' => -7.8118994,
                    'lng' => 110.3632557
                ],
                'draggable' => false
            ]
        ];
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
