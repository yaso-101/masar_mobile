<?php

use App\Http\Controllers\Auth\LoggedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DriverController;
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
    Route::post('/studentfirst', [StudentController::class, 'storeStudentFirst']);
    Route::get('/driver', [DriverController::class, 'Allcolleges']);
    Route::post('/driver/assign-student', [DriverController::class, 'matchWithStudent']);
    Route::view('/search-driver', 'components.pages.search-driver');

    // The Tracking & Ride Action Routes
    Route::get('/track/{id}', [DriverController::class, 'trackRide']);
    Route::post('/driver/pickup/{id}', [DriverController::class, 'pickupStudent']); // <-- Added Pickup!
    Route::post('/driver/dropoff', [DriverController::class, 'dropoffStudent']);
});
