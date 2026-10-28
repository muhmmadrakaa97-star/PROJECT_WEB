<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengaduanController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/redirect-dashboard', function () {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return redirect('/dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | MAHASISWA
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:mahasiswa'])->group(function () {

        Route::get(
            '/dashboard',
            [PengaduanController::class, 'indexMahasiswa']
        )->name('mahasiswa.dashboard');

        // Membuat pengaduan
        Route::post(
            '/pengaduan',
            [PengaduanController::class, 'store']
        )->name('pengaduan.store');

        // Edit pengaduan
        Route::get(
            '/pengaduan/{id}/edit',
            [PengaduanController::class, 'edit']
        )->name('pengaduan.edit');

        // Update pengaduan
        Route::put(
            '/pengaduan/{id}',
            [PengaduanController::class, 'update']
        )->name('pengaduan.update');

        // Hapus pengaduan
        Route::delete(
            '/pengaduan/{id}',
            [PengaduanController::class, 'destroy']
        )->name('pengaduan.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])->group(function () {

        Route::get(
            '/admin/dashboard',
            [PengaduanController::class, 'indexAdmin']
        )->name('admin.dashboard');

        // Edit pengaduan
        Route::get(
            '/admin/pengaduan/{id}/edit',
            [PengaduanController::class, 'edit']
        )->name('admin.pengaduan.edit');

        // Update pengaduan
        Route::put(
            '/admin/pengaduan/{id}',
            [PengaduanController::class, 'update']
        )->name('admin.pengaduan.update');

        // Ubah status
        Route::put(
            '/admin/pengaduan/{id}/status',
            [PengaduanController::class, 'updateStatus']
        )->name('admin.pengaduan.status');

        // Hapus pengaduan
        Route::delete(
            '/admin/pengaduan/{id}',
            [PengaduanController::class, 'destroy']
        )->name('admin.pengaduan.destroy');
    });
});
