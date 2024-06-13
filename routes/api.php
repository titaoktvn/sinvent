<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// [invent-01] Semua kategori
Route::get('kategori', [KategoriController::class, 'getAPIKategori']);

// [invent-02] Buat Kategori Baru
Route::post('kategori', [KategoriController::class, 'createAPIKategori']);

// [invent-03] Salah Satu Kategori
Route::get('kategori/{id}', [KategoriController::class, 'showAPIKategori']);

// [invent-04] Hapus Kategori
Route::delete('kategori/{id}', [KategoriController::class, 'deleteAPIKategori']);

// [invent-05] Update Salah Satu Kategori
Route::put('kategori/{id}', [KategoriController::class, 'updateAPIKategori']);