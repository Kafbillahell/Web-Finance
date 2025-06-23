<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DompetController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\TransaksiTabunganController;

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

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/form', [DashboardController::class, 'form'])->name('form');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile', [DashboardController::class, 'destroyProfile'])->name('profile.delete');

    Route::resource('kategori', KategoriController::class)->except(['show']);
    Route::resource('transaksi', TransaksiController::class);
    Route::resource('dompet', DompetController::class)->except(['show']);
    Route::get('/dompet/{dompet}', [DompetController::class, 'show'])->name('dompet.show');
    Route::resource('tabungan', TabunganController::class);
    Route::post('/tabungan/{id}/add-saldo', [TabunganController::class, 'addSaldo'])->name('tabungan.addSaldo');
    Route::post('/tabungan/{id}/withdraw-saldo', [TabunganController::class, 'withdrawSaldo'])->name('tabungan.withdrawSaldo');
    Route::get('/history-tabungan', [TabunganController::class, 'history'])->name('transaksi.tabungan');

    Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');

    // Dompet fitur tambahan (deposit & withdraw)
    Route::post('/dompet/{id}/deposit', [DompetController::class, 'deposit'])->name('dompet.deposit');
    Route::post('/dompet/{id}/withdraw', [DompetController::class, 'withdraw'])->name('dompet.withdraw');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/export/transaksi/{type}', [TransaksiController::class, 'exportTransaksi'])->name('export.transaksi');

});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
    // CRUD Resource
    Route::resource('users', UserController::class);
});
