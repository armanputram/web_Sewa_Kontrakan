<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrasiPemilikController;
use App\Http\Controllers\RegistrasiPenyewaController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\PengelolaController;


Route::post('register', [AuthController::class, 'registerpengelola']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//route registrasi
Route::post('/registrasipemilik', [RegistrasiPemilikController::class, 'register']);
Route::post('/registrasipenyewa', [RegistrasiPenyewaController::class, 'register']);
//route login
Route::post('/loginpemilik', [RegistrasiPemilikController::class, 'login'])->name('loginpemilik');
Route::post('/loginpenyewa', [RegistrasiPenyewaController::class, 'login']);

// Edit profil penyewa
Route::put('/editprofilpenyewa/{idPenyewa}', [RegistrasiPenyewaController::class, 'editProfile']);
// Edit profil pemilik
Route::put('/editprofilpemilik/{idPemilik}', [RegistrasiPemilikController::class, 'editProfile']);

//input dan get properti
Route::post('inputkontrakan', [PropertiController::class, 'create']);
Route::get('data', [PropertiController::class, 'index']);


Route::middleware(['auth:registrasi_pemilik'])->group(function () {
    Route::get('databyid', [PropertiController::class, 'indexbyid']);
});


Route::middleware(['auth:registrasi_pemilik'])->group(function () {
    Route::delete('akun/{idPemilik}', [RegistrasiPemilikController::class, 'destroy']);
});

Route::middleware(['auth:registrasi_pemilik'])->group(function () {
    Route::put('update', [RegistrasiPemilikController::class, 'updateProfile']);
});

Route::middleware(['auth:registrasi_penyewa'])->group(function () {
    Route::delete('akunpn/{idPenyewa}', [RegistrasiPenyewaController::class, 'destroy']);
});



Route::post('/update-status/{id}', [PengelolaController::class, 'updateStatus']);
