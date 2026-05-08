**Bug #1 — Broken Access Control**

Di `routes/api.php`, route `/admin/dashboard` didaftarkan tanpa middleware apapun. Akibatnya siapapun bisa akses halaman admin langsung lewat URL, bahkan tanpa login. Di `AdminController.php` juga tidak ada pengecekan role atau `authorize()` sama sekali. Fixnya pakai `Route::middleware(['auth', 'role:admin'])` di routes, dan `$this->authorize()` di controller.

---

**Bug #2 — Authentication Bypass**

Di `AuthController.php`, fungsi login hanya cari user by email, lalu langsung panggil `Auth::loginUsingId($user->id)` tanpa cek password sama sekali. Jadi siapapun yang tahu email orang lain bisa login ke akunnya tanpa perlu tahu passwordnya. Fixnya pakai `Auth::attempt(['email' => ..., 'password' => ...])` yang otomatis verifikasi password,
---

**Bug #3 — Plaintext Password**

Di `UserController.php`, saat register password langsung disimpan dengan `'password' => $request->password`. Jadi yang masuk ke database adalah teks aslinya, misalnya "rahasia123". Kalau database bocor, semua password langsung terbaca. Di `User.php` juga `password` tidak dimasukkan ke `$hidden`, jadi ikut muncul kalau data user di-serialize ke JSON. Fixnya cukup ganti jadi `Hash::make($request->password)`, atau di Laravel 10+ bisa pakai cast `'password' => 'hashed'` di model supaya otomatis ter-hash setiap kali di-assign.
