<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroomingController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk menampilkan produk groomingg
Route::get('/grooming', [GroomingController::class, 'index'])->name('grooming.index');
// Route untuk menampilkan formulir tambah layanan grooming
Route::get('/grooming/create', [GroomingController::class, 'create'])->name('grooming.create');
Route::post('/grooming/store', [GroomingController::class, 'store'])->name('grooming.store');


