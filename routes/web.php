<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/armada-mobil', [VehicleController::class, 'index'])->name('vehicles.cars');
Route::get('/armada-motor', [VehicleController::class, 'index'])->name('vehicles.motors');
Route::get('/armada/{slug}', [VehicleController::class, 'show'])->name('vehicles.show');
