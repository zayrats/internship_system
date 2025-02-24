<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
// use Session;

class Logincontroller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->back();
        } else {
            return view('login');
        }
    }


    public function actionlogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            switch ($user->role) {
                case 'Mahasiswa':
                    return redirect()->route('studentdashboard');
                    break;
                case 'Perusahaan':
                    return redirect()->route('companydashboard');
                    break;
                case 'Dosen Koordinator':
                    return redirect()->route('lecturerdashboard');
                    break;

                case 'Admin':
                    return redirect()->route('admindashboard');
                    break;
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Role tidak terdaftar.');
            }
        } else {
            Session::flash('error', 'Email atau Password Salah');
            return redirect()->route('login');
        }
    }



    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
    public function register()
    {
        $roles = User::select('role')->distinct()->pluck('role');

        return view('register', compact('roles'));
    }


    public function actionregister(Request $request)
    {
        $user = User::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
        ]);

        Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan username dan password.');
        return redirect('login');
    }
}
