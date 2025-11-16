<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('homepage');
});


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('username', $data['username'])->first();
    if (!$user || !Hash::check($data['password'], $user->password)) {
        return back()->withInput()->with('error', 'Username atau password salah.');
    }

    session(['user_id' => $user->id, 'username' => $user->username, 'role' => $user->role ?? null]);

    return redirect('/')->with('success', 'Login berhasil.');
});

Route::post('/logout', function (Request $request) {
    $request->session()->flush();
    return redirect('/')->with('success', 'Anda telah logout.');
})->name('logout');

Route::get('/login', function () {
    return view('login');
});