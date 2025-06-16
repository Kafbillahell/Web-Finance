<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\DompetController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);
Route::resource('kategori', KategoriController::class);
Route::resource('pengeluaran', PengeluaranController::class);
Route::resource('pemasukan', PemasukanController::class);
Route::resource('dompets', DompetController::class);
Route::resource('tabungan', TabunganController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/form', [DashboardController::class, 'form'])->name('form');
