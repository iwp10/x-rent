<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/armada-mobil', [CatalogController::class, 'cars'])->name('catalog.cars');
Route::get('/armada-motor', [CatalogController::class, 'motors'])->name('catalog.motors');




