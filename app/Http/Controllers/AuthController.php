<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        // Hardcoded credentials
        $validUsername = 'aldmic';
        $validPassword = '123abc123';

        if ($request->username === $validUsername && $request->password === $validPassword) {
            // Langsung redirect tanpa session
            return redirect()->route('movies.index');
        }

        return back()->withErrors(['credentials' => 'Username atau password salah'])->withInput();
    }

    public function logout(Request $request)
    {
        // Tanpa session, cukup redirect ke login
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}