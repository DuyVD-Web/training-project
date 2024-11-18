<?php

use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserInformationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::any('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::prefix('register')->name('register')->group(function () {
        Route::get('/', [RegisterUserController::class, 'create']);
        Route::post('/', [RegisterUserController::class, 'store'])->name('store');
    });
    Route::prefix('login')->name('login')->group(function () {
        Route::get('/', [SessionController::class, 'create']);
        Route::post('/', [SessionController::class, 'store'])->name('store');
    });
});

Route::middleware('auth')->prefix('/user')->name('user')->group(function () {
    Route::prefix('/info')->name('info')->group(function () {
        Route::get('/', [UserInformationController::class, 'show']);
        Route::post('/', [UserInformationController::class, 'update'])->name('update');
        Route::post('/password', [UserInformationController::class, 'updatePassword'])->name('password');
    });
});

