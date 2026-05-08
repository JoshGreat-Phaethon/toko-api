<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel users versi Yoga.
     * Kolom 'password' adalah string biasa — tidak ada constraint khusus.
     * Tidak ada kolom 'email_verified_at' karena verifikasi email tidak dipasang.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');         // ❌ Akan diisi plaintext
            $table->string('role')->default('user'); // 'user' | 'admin'
            // ❌ Tidak ada: $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
