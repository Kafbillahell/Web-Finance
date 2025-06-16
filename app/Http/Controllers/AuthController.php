<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        // Cek autentikasi
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect sesuai dengan role pengguna
            if ($user->role === 'admin') {
                return redirect('/dashboard');  // Halaman admin
            }

            if ($user->role === 'users') {
                return redirect('/dashboard');  // Halaman kasir
            }

            abort(403, 'Unauthorized role.');
        }

        return back()->wiesethErrors([
            'username' => 'Th credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
