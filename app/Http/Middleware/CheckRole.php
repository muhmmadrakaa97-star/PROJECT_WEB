<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        if ($user->role !== $role) {
            // Redirect sesuai role jika pengguna mencoba mengakses halaman role lain
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }
            if ($user->role === 'mahasiswa') {
                return redirect('/dashboard');
            }
            abort(403, 'Akses ditolak: role pengguna tidak valid.');
        }

        return $next($request);
    }
}