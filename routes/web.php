<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
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

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::prefix("dashboard/{role}")->group( function () {

        Route::get('password-update', [AuthController::class, 'showPasswordUpdateForm'])->name('password.update');
        Route::post('password-update', [AuthController::class, 'updatePassword'])->name('password.update.submit');

        Route::get('', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get("profile", [DashboardController::class, 'profile'])
            ->name('profile');

        Route::put("profile", [DashboardController::class, 'updateProfile'])
            ->name('profile.update');

        Route::get('course-registration', [CourseRegistrationController::class, 'create'])
            ->name('course.register.create');

        Route::post('course-registration', [CourseRegistrationController::class, 'submit'])
            ->name('student.course.register.submit');


        // Admin student routes
        Route::prefix("students")->group( function () {

           Route::get('', [StudentController::class, 'index'])->name('students.index');
           Route::get('/register', [StudentController::class, 'create'])->name('students.create');
           Route::post('/register', [StudentController::class, 'store'])->name('students.store');
        });
    });
});
