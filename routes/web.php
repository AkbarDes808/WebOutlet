<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\MarinasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Awal (Bisa halaman login atau landing page)
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup route yang hanya bisa diakses setelah login (middleware 'auth')
Route::middleware(['auth', 'verified'])->group(function () {

    // Route untuk Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk fitur Bahan (Inventory)
    Route::get('/bahans', [BahanController::class, 'index'])->name('bahans.index');
    Route::get('/bahans/create', [BahanController::class, 'create'])->name('bahans.create');
    Route::post('/bahans', [BahanController::class, 'store'])->name('bahans.store');

    // Route untuk fitur History
    Route::get('/history', [BahanController::class, 'history'])->name('bahans.history');

    // Route untuk Menu Outlet
    Route::get('/outlets', [OutletController::class, 'index'])->name('outlets.index');

    // Route untuk fitur Marinasi
    Route::get('/marinasi', [MarinasiController::class, 'index'])->name('marinasi.index');
    Route::post('/marinasi', [MarinasiController::class, 'store'])->name('marinasi.store');

    // Route untuk Profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat route untuk autentikasi (login, register, dll.)
require __DIR__.'/auth.php';