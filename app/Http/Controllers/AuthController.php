<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman form login.
     * Route: GET /login
     */
    public function showLogin()
    {
        // Jika sudah login, langsung redirect ke dashboard admin
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login dari form.
     * Route: POST /login
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan (cegah session fixation)
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // Jika gagal, kembali ke form dengan pesan error
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    /**
     * Proses logout.
     * Route: POST /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
