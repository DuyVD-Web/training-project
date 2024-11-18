<?php

use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::any('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::prefix('register')->group(function () {
        Route::get('/', [RegisterUserController::class, 'create'])->name('register');
        Route::post('/', [RegisterUserController::class, 'store'])->name('register.store');
    });
    Route::prefix('login')->group(function () {
        Route::get('/', [SessionController::class, 'create'])->name('login');
        Route::post('/', [SessionController::class, 'store'])->name('login.store');
    });
});

