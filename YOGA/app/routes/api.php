<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// ─── Public routes ───────────────────────────────────────────────
Route::get('/', fn() => view('welcome'));

Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register',[UserController::class, 'register']);

// ─── User dashboard (hanya yang login) ──────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

// ─── BUG #1: Halaman admin TANPA middleware ──────────────────────
// Harusnya pakai: Route::middleware(['auth', 'role:admin'])
// Tapi Yoga lupa pasang — siapapun bisa akses langsung lewat URL!
Route::get('/admin/dashboard', [AdminController::class, 'index']);
Route::get('/admin/users',     [AdminController::class, 'users']);
Route::get('/admin/orders',    [AdminController::class, 'orders']);
