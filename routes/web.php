<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleOAuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('homepage');
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

});