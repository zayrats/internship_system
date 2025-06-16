<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
// use Session;

class LoginController
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
                    return redirect()->route('home');
                    break;
                case 'Perusahaan':
                    return redirect()->route('companydashboard');
                    break;
                case 'Dosen Koordinator':
                    return redirect()->route('lecturerdashboard');
                    break;
                case 'Pustakawan':
                    return redirect()->route('pustakawan');
                    break;

                case 'Admin':
                    return redirect()->route('admin.users');
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
        DB::beginTransaction(); // 🔹 Mulai transaksi database
        request()->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            // 'role' => 'required',
        ]);
        try {
            // 🔹 Simpan data user
            $user = User::create([
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
                'role' => ('Mahasiswa'),
            ]);
            // 🔹 Jika user mendaftar sebagai mahasiswa, tambahkan ke tabel students
            if ($user->role === 'Mahasiswa') {
                Students::create([
                    'user_id' => $user->user_id, // 🔹 Pastikan tabel students punya kolom user_id
                ]);
            }

            //jika user mendaftar sebagai perusahaan, tambahkan ke tabel company
            // if ($user->role === 'Perusahaan') {
            //     Company::create([
            //         'user_id' => $user->user_id, // 🔹 Pastikan tabel company punya kolom user_id
            //     ]);
            //     // dd(request()->all());
            // }
            DB::commit(); // 🔹 Simpan perubahan jika semua berhasil

            Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan username dan password.');
            return redirect('login') ->with('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan username dan password.');
        } catch (\Exception $e) {
            DB::rollback(); // ❌ Batalkan semua perubahan jika terjadi error

            Session::flash('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.');
        }
    }
}
