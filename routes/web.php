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
// Route::resource('kategori', KategoriController::class);
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');

Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');


Route::resource('pengeluaran', PengeluaranController::class);
Route::resource('pemasukan', PemasukanController::class);
Route::resource('dompets', DompetController::class);
Route::resource('tabungan', TabunganController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
