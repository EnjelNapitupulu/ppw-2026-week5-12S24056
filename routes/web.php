<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', fn () => redirect()->route('buku.index'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');

// Katalog boleh dilihat publik (hanya baca)
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// Rute mutasi: wajib login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('buku', BukuController::class)->except(['index']);
    Route::resource('kategori', KategoriController::class);

    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store']);
    Route::patch('peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembali'])
        ->name('peminjaman.kembali');
});