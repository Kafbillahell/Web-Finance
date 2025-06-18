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

// Routes untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

// Logout hanya bisa dilakukan jika sudah login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Routes untuk yang sudah login
Route::middleware('auth')->group(function () {
    // Dashboard & halaman form
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/form', [DashboardController::class, 'form'])->name('form');

    // CRUD Resource
    Route::resource('users', UserController::class);
    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('dompet', DompetController::class);
    Route::resource('tabungan', TabunganController::class);

    // Tabungan fitur tambahan
    Route::post('/tabungan/{id}/add-saldo', [TabunganController::class, 'addSaldo'])->name('tabungan.addSaldo');
    Route::post('/tabungan/{id}/withdraw-saldo', [TabunganController::class, 'withdrawSaldo'])->name('tabungan.withdrawSaldo');
    Route::get('/history-tabungan', [TabunganController::class, 'history'])->name('tabungan.history');

    Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');

    // Dompet fitur tambahan (deposit & withdraw)
    Route::post('/dompet/{id}/deposit', [DompetController::class, 'deposit'])->name('dompet.deposit');
    Route::post('/dompet/{id}/withdraw', [DompetController::class, 'withdraw'])->name('dompet.withdraw');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
});
