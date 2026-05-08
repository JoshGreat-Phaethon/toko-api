<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * BUG #3 — Plaintext Password Storage
     *
     * Password disimpan langsung ke database tanpa di-hash.
     * Siapapun yang buka tabel users bisa baca semua password pelanggan.
     *
     * Yang seharusnya: 'password' => Hash::make($request->password)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            // tidak ada 'confirmed' → tidak perlu konfirmasi password
        ]);

        // ❌ SALAH: password disimpan apa adanya (plaintext)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // e.g. "rahasia123" tersimpan mentah
        ]);

        // Langsung login setelah register (tanpa verifikasi email)
        auth()->login($user);

        return redirect()->route('dashboard');
    }

    public function show($id)
    {
        // Bonus bug kecil: tidak ada pengecekan apakah $id milik user yang login
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }
}
