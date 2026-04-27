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

        // SESSION
        session([
            'user_id' => $user->id,
            'role' => $user->role,
            'name' => $user->name
        ]);

        // REDIRECT SESUAI ROLE
        if ($user->role == 'admin') {
            return redirect('/admin');
        } elseif ($user->role == 'petugas') {
            return redirect('/petugas');
        } else {
            return redirect('/siswa');
        }
    }

    // REGISTER 

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'nis' => 'required|unique:users'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nis' => $request->nis,
            'role' => 'siswa'
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login');
    }

    //  LOGOUT 

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}