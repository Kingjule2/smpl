<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes (hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes (hanya bisa diakses jika sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');
    Route::post('/dashboard/kolam', [DashboardController::class, 'storeKolam'])->name('dashboard.storeKolam');
    Route::post('/dashboard/kolam/{kolam}/move', [DashboardController::class, 'confirmMove'])->name('dashboard.confirmMove');
    Route::post('/dashboard/kolam/{kolam}/harvest', [DashboardController::class, 'confirmHarvest'])->name('dashboard.confirmHarvest');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
