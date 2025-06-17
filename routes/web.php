<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DompetController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/form', [DashboardController::class, 'form'])->name('form');

    Route::resource('users', UserController::class);

    Route::resource('kategori', KategoriController::class)->except(['show']);
    
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('dompet', DompetController::class);
    Route::resource('tabungan', TabunganController::class);
    Route::post('/tabungan/{id}/add-saldo', [TabunganController::class, 'addSaldo'])->name('tabungan.addSaldo');
});
