<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertiController;

// Halaman utama
Route::get('/', [PengelolaController::class, 'index'])->name('index');

// Menampilkan form login dengan nama rute yang ditambahkan
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPengelola'])->name('loginPengelola');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Menampilkan form registrasi
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerpengelola'])->name('registerpengelola');

// Middleware 'auth' akan memastikan bahwa pengguna harus terautentikasi untuk mengakses rute-rute di bawah ini
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PengelolaController::class, 'dashboard'])->name('dashboard');
    Route::post('/update-status/{id}', [PengelolaController::class, 'updateStatus']);
    Route::get('data', [PropertiController::class, 'index']);
    Route::get('/riwayat', [PengelolaController::class, 'riwayat'])->name('riwayat');
});
