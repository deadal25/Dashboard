<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the application login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('beranda');
        }

        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect()->intended(route('beranda'))
                ->with('success', "Selamat datang, {$user->name}! Anda login sebagai {$user->role_name}.");
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * 1-Click quick login for testing Super Admin or HR Admin.
     */
    public function quickLogin(string $role)
    {
        $email = ($role === 'super_admin') ? 'superadmin@map-in.com' : 'hradmin@map-in.com';
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            return redirect()->route('beranda')
                ->with('success', "Beralih peran berhasil. Anda sekarang aktif sebagai {$user->role_name}.");
        }

        return redirect()->route('login')->with('error', 'Akun tidak ditemukan.');
    }


    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
