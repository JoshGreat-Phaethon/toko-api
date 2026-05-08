<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * BUG #2 — Authentication Bypass
     *
     * Kode ini TIDAK memverifikasi password sama sekali.
     * Cukup tahu email seseorang → langsung bisa login ke akunnya.
     *
     * Yang seharusnya: Auth::attempt(['email' => ..., 'password' => ...])
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            // password tidak di-validate karena memang tidak dicek!
        ]);

        // Cari user by email
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // ❌ SALAH: langsung login pakai ID tanpa cek password
            Auth::loginUsingId($user->id);

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Email tidak ditemukan.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
