<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * BUG #3 lanjutan — 'password' ada di $fillable tapi tidak ada mutator
     * yang otomatis hash. Jadi User::create(['password' => 'abc']) simpan 'abc' mentah.
     *
     * Laravel punya konvensi: kalau pakai Bcrypt, biasanya ada:
     *   protected function password(): Attribute {
     *       return Attribute::make(set: fn($v) => Hash::make($v));
     *   }
     * Tapi Yoga tidak pasang ini.
     */
    protected $fillable = [
        'name',
        'email',
        'password', // ❌ tidak ada auto-hash
        'role',     // 'user' | 'admin'
    ];

    protected $hidden = [
        // ❌ 'password' tidak di-hidden → ikut ter-serialize ke JSON/array
        // Harusnya ada 'password' di sini
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        // ❌ tidak ada: 'password' => 'hashed' (fitur Laravel 10+)
    ];

    // Helper sederhana untuk cek role — tapi tidak pernah dipanggil di route!
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
