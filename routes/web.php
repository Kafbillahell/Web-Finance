<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\DompetController;
use App\Http\Controllers\TabunganController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::resource('users', UserController::class);
Route::resource('/kategori', KategoriController::class);
Route::resource('pengeluaran', PengeluaranController::class);
Route::resource('pemasukan', PemasukanController::class);
Route::resource('dompets', DompetController::class);
Route::resource('tabungan', TabunganController::class);
