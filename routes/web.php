<?php

use App\Http\Controllers\Auth\LoggedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ----------------------------------------------------
// GUEST ROUTES (Not logged in)
// ----------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::view('/', 'components.form.auth');
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [LoggedUserController::class, 'create']);
    Route::post('/login', [LoggedUserController::class, 'store']);
});


// ----------------------------------------------------
// SUBSCRIPTION (Logged in, paid or not)
// Unpaid users are sent here from every other page ('subscribed' middleware)
// ----------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'show']);
    Route::post('/logout', [LoggedUserController::class, 'destroy']);
});


// ----------------------------------------------------
// SHARED AUTH ROUTES (Logged in + paid, driver or student)
// ----------------------------------------------------
Route::middleware(['auth', 'subscribed', 'role:driver|student'])->group(function () {
    Route::get('/student', [StudentController::class, 'Allcolleges']);
    Route::get('/driver', [DriverController::class, 'Allcolleges']);
});

// ----------------------------------------------------
// ACCOUNT ROUTES (Logged in + paid, any role) - opened from the sidebar
// ----------------------------------------------------
Route::middleware(['auth', 'subscribed'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});


// ----------------------------------------------------
// STUDENT ONLY ROUTES
// ----------------------------------------------------
Route::middleware(['auth', 'subscribed', 'role:student'])->group(function () {
    Route::post('/studentfirst', [StudentController::class, 'storeStudentFirst']);
    Route::get('/search-driver/{ride}', [StudentController::class, 'searchDriver']);
    Route::get('/student/check-ride/{ride}', [StudentController::class, 'checkRideStatus']);

    // Map view once a driver is found
    Route::get('/found-ride/{ride}', [StudentController::class, 'foundRide']);
});


// ----------------------------------------------------
// DRIVER ONLY ROUTES
// ----------------------------------------------------
Route::middleware(['auth', 'subscribed', 'role:driver'])->group(function () {
    Route::post('/driver/assign-student', [DriverController::class, 'matchWithStudent']);
    Route::get('/track/{id}', [DriverController::class, 'trackRide']);
    Route::post('/driver/pickup/{id}', [DriverController::class, 'pickupStudent']);
    Route::post('/driver/dropoff', [DriverController::class, 'dropoffStudent']);
    Route::post('/driver/update-location', [DriverController::class, 'updateLocation']);
});
