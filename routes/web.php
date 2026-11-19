<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;


// ========================================
// VIEW ROUTES
// ========================================
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index']);


// ========================================
// API ROUTES
// ========================================
Route::prefix('api')->group(function () {

    // Authentication
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', 'login')->name('api.auth.login');
        Route::post('/register', 'register')->name('api.auth.register');

        Route::post('/logout', 'logout')
            ->name('api.auth.logout');
    });
    // loan routes
    Route::prefix('loans')->controller(LoanController::class)->group(function () {
        Route::post('/create', 'createLoan')->name('loans.store');
        Route::post('/{loan}/approve', 'approve')->name('loans.approve');
        Route::post('/{loan}/return', 'returnBook')->name('loans.return');
    });
});