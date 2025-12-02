<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TravelsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TiketController;

Route::get('/', function () {
    return view('homepage');
})->name('home');
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
    Route::get('/travels', [TravelsController::class, 'index'])->name('travels.index');

    // Create & store are admin only
    Route::get('/create/flights', [TravelsController::class, 'create'])
        ->name('travels.create')
        ->middleware('admin');
    Route::post('/travels/store', [TravelsController::class, 'store'])
        ->name('travels.store')
        ->middleware('admin');

    //tiket
    Route::get('/tiket/pesan/{id}', [TiketController::class, 'create'])->name('tiket.create');
    Route::post('/tiket/pesan', [TiketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/sukses', function(){
        return view('travels.index');
    })->name('user.tiket.sukses');

    // bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel.post');
    Route::get('/bookings/{id}/modify', [BookingController::class, 'modify'])->name('bookings.modify');
    Route::get('/bookings/{id}/alternatives', [BookingController::class, 'getAlternativeFlights'])->name('bookings.alternatives');
    Route::post('/bookings/{id}/change-flight', [BookingController::class, 'changeToFlight'])->name('bookings.changeFlight');
    Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::post('/bookings/{id}/delete', [BookingController::class, 'destroy'])->name('bookings.delete');

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
