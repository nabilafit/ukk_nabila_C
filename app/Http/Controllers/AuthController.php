<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah!');
    }

    session([
            'user_id' => $user->id,
            'role' => $user->role,
            'name' => $user->name
        ]);

    // redirect sesuai role
        if ($user->role == 'admin') {
            return redirect('/admin');
        } elseif ($user->role == 'petugas') {
            return redirect('/petugas');
        } else {
            return redirect('/siswa');
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
