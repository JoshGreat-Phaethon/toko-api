<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * BUG #1 — Broken Access Control
     *
     * Controller ini tidak mengecek apakah user yang request adalah admin.
     * Tidak ada: $this->middleware('auth'), Gate::authorize(), atau $this->authorize()
     *
     * Siapapun yang tau URL /admin/dashboard bisa masuk — bahkan tanpa login!
     */

    public function index()
    {
        // ❌ Tidak ada: if (!auth()->user()?->isAdmin()) abort(403);
        // ❌ Tidak ada: $this->authorize('admin');
        // ❌ Tidak ada middleware check

        $totalUsers  = User::count();
        $totalOrders = 0; // anggap ada model Order

        return view('admin.dashboard', compact('totalUsers', 'totalOrders'));
    }

    public function users()
    {
        // ❌ Semua data pelanggan bisa diakses siapapun
        $users = User::all(); // termasuk email, password plaintext, dll

        return view('admin.users', compact('users'));
    }

    public function orders()
    {
        // ❌ Data order semua pelanggan terekspos
        $orders = []; // anggap Order::all()

        return view('admin.orders', compact('orders'));
    }
}
