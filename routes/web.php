<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelsController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('homepage');
});
Route::get('/profile', function () {
    return view('profile');
}); 

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google OAuth Routes
    Route::get('/auth/google', [GoogleOAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleOAuthController::class, 'callback'])->name('google.callback');
});

// PROTECTED ROUTES (USER LOGIN)
Route::middleware('auth')->group(function () {

    // logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // profile page
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // update username + email
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // update password
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    
    // travels
    Route::get('/travels', [TravelsController::class, 'index']);
    Route::get('/create/flights', [TravelsController::class, 'create']);
    Route::post('/travels', [TravelsController::class, 'store'])->name('travel.store');


    // bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/bookings/{id}/modify', [BookingController::class, 'modify'])->name('bookings.modify');
    Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');

});

Route::middleware('auth')->group(function () {

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // PROFILE PAGE
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // UPDATE PROFILE (username + email)
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // UPDATE PASSWORD
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
