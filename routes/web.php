<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VehicleManagementController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Public Website Routes (X-RENT SPA)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/armada-mobil', [VehicleController::class, 'index'])->name('vehicles.cars');
Route::get('/armada-motor', [VehicleController::class, 'index'])->name('vehicles.motors');
Route::get('/armada/{slug}', [VehicleController::class, 'show'])->name('vehicles.show');

// Track Booking & Online Reservation (Public)
Route::get('/cek-pesanan', [BookingController::class, 'track'])->name('booking.track');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::get('/login', fn () => redirect()->route('login'));
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Panel Routes (Task 4.1 & 4.2)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Vehicle Management (CRUD)
    Route::get('/vehicles', [VehicleManagementController::class, 'index'])->name('vehicles.index');
    Route::post('/vehicles', [VehicleManagementController::class, 'store'])->name('vehicles.store');
    Route::put('/vehicles/{vehicle}', [VehicleManagementController::class, 'update'])->name('vehicles.update');
    Route::patch('/vehicles/{vehicle}/status', [VehicleManagementController::class, 'updateStatus'])->name('vehicles.status');
    Route::delete('/vehicles/{vehicle}', [VehicleManagementController::class, 'destroy'])->name('vehicles.destroy');

    // Booking Management
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/status', [BookingManagementController::class, 'updateStatus'])->name('bookings.status');
});
