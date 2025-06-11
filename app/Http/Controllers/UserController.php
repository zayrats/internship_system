<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController
{
    public function index()
    {
        $users = User::orderBy('username', 'asc')->paginate(10); // Ambil semua user dengan pagination
        return view('admin.admindashboard', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Admin,Mahasiswa,Perusahaan',
        ]);

        User::create([
            'username' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|in:Admin,Mahasiswa,Perusahaan',
        ]);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'Role user berhasil diperbarui');
    }


    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }
}
