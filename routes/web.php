<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Guest-only routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Authenticated-only routes
Route::middleware('auth')->group(function () {
    Route::get('/result', function () {
        return view('result');
    })->name('result');

    Route::get('/course-registration', function () {
        return view('course-registration');
    })->name('course.register');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::prefix("dashboard/{role}")->group( function () {
        Route::get('', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get("profile", [DashboardController::class, 'profile'])
            ->name('profile');
    });
});
