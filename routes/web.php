<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\SchoolSessionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentResultController;
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

        Route::prefix("departments")->group( function () {
            Route::get('', [DepartmentController::class, 'index'])->name('departments.index');
            Route::get('add', [DepartmentController::class, 'create'])->name('departments.create');
            Route::post('add', [DepartmentController::class, 'store'])->name('departments.store');
        });

        Route::prefix("courses")->group(function () {
            Route::get('', [CourseController::class, 'index'])->name('courses.index');
            Route::get('add', [CourseController::class, 'create'])->name('courses.create');
            Route::post('add', [CourseController::class, 'store'])->name('courses.store');
        });

        Route::prefix('lecturers')->group(function () {
            Route::get('', [LecturerController::class, 'index'])->name('lecturers.index');
            Route::get('add', [LecturerController::class, 'create'])->name('lecturers.create');
            Route::post('add', [LecturerController::class, 'store'])->name('lecturers.store');
            Route::post('{lecturer}/assign-course', [LecturerController::class, 'assignCourse'])->name('lecturers.course.assign.store');
            Route::get('{lecturer}/courses', [LecturerController::class, 'viewCourses'])->name('lecturers.courses');
            Route::get('{lecturer}/students', [LecturerController::class, 'viewStudents'])->name('lecturers.students');
        });

        Route::prefix('sessions')->group(function () {
            Route::get('', [SchoolSessionController::class, 'index'])->name('sessions.index');
            Route::get('create', [SchoolSessionController::class, 'create'])->name('sessions.create');
            Route::post('create', [SchoolSessionController::class, 'store'])->name('sessions.store');
            Route::get('{sessionId}/update', [SchoolSessionController::class, 'show'])->name('sessions.show');
            Route::post('{sessionId}/update', [SchoolSessionController::class, 'update'])->name('sessions.update');
        });

        Route::prefix("results")->group(function () {
            Route::get('', [StudentResultController::class, 'index'])->name('results.index');
            Route::get('add', [StudentResultController::class, 'create'])->name('results.create');
            Route::post('add', [StudentResultController::class, 'store'])->name('results.store');
            Route::get('check', [StudentResultController::class, 'index'])->name('results.check');
        });
    });
});
