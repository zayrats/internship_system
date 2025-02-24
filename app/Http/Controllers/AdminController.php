<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class AdminController {


public function admindashboard(Request $request)
{
    return view('admindashboard');
}

public function detailuser(Request $request)
{
    $id = $request->id;
    $user = User::find($id);
    return view('detailuser', compact('user'));
}

public function updateuser(Request $request)
{
    $id = $request->id;
    $user = User::find($id);
    $user->email = $request->email;
    $user->username = $request->username;
    $user->password = Hash::make($request->password);
    $user->role = $request->role;
    $user->save();
    return redirect()->route('admindashboard');
}

public function deleteuser(Request $request)
{
    $id = $request->id;
    $user = User::find($id);
    $user->delete();
    return redirect()->route('admindashboard');
}

    }
