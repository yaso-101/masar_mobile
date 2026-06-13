<?php

use App\Http\Controllers\Auth\LoggedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/', 'components.form.auth');
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [LoggedUserController::class, 'create']);
    Route::post('/login', [LoggedUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard');
    Route::get('/student', [StudentController::class, 'Allcolleges']);
    ROute::post('/studentfirst', [StudentController::class, 'storeStudentFirst']);
    Route::view('/driver', 'components.pages.driver');

});
