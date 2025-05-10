<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name("home");

Route::get('/login', function () {
    return view('login');
})->name("login");

Route::get('/result', function () {
    return view('result');
})->name("result");

Route::get('/course-registration', function () {
    return view('course-registration');
})->name("course.register");
