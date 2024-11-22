<?php

use App\Http\Controllers\ChangeEmailController;
use App\Http\Controllers\DemoVerificationController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserInformationController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\VerificationController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth','verified');

Route::middleware('auth')->group(function () {
    Route::any('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::prefix('register')->name('register')->group(function () {
        Route::get('/', [RegisterUserController::class, 'create']);
        Route::post('/', [RegisterUserController::class, 'store'])->name('.store');
    });
    Route::prefix('login')->name('login')->group(function () {
        Route::get('/', [SessionController::class, 'create']);
        Route::post('/', [SessionController::class, 'store'])->name('.store');
    });
});

Route::middleware('auth')->prefix('/email')->name('verification')->group(function () {
    Route::get('/verify', [VerificationController::class,'verify'])->name('.notice');

    Route::get('/verify/{id}/{hash}', [VerificationController::class,'verifyEmail'])->middleware(['signed'])->name('.verify');

    Route::post('/verification-notification', [VerificationController::class,'resend'])->middleware(['throttle:6,1'])->name('.send');
});


Route::middleware(['auth','verified'])->prefix('/user')->name('user')->group(function () {
    Route::prefix('/info')->name('.info')->group(function () {
        Route::get('/', [UserInformationController::class, 'show']);
        Route::post('/', [UserInformationController::class, 'update'])->name('.update');
        Route::post('/password', [UserInformationController::class, 'updatePassword'])->name('.password');


        Route::get('/email', [ChangeEmailController::class, 'index'])->name('.changeEmail');
        Route::post('/email', [ChangeEmailController::class, 'sendChangeEmail'])->name('.sendChangeEmail');
        Route::get('/email/verify/{token}', [ChangeEmailController::class, 'verifyChangeEmail'])->name('.verifyChangeEmail');
    });
});


//Demo
Route::middleware('auth')->prefix('/demo')->name('demo')->group(function () {
    Route::get('/email/verify', [DemoVerificationController::class, 'notice'])
        ->name('.verification.notice');

    Route::get('/email/verify/{token}', [DemoVerificationController::class, 'verify'])
        ->name('.verification.verify');

    Route::post('/email/verification-notification',
        [DemoVerificationController::class, 'sendVerification'])
        ->middleware(['throttle:6,1'])
        ->name('.verification.send');
});


// Admin
Route::middleware(['auth', AdminMiddleware::class])->prefix('/admin')->name('admin')->group(function () {
    Route::get('/users',[UserListController::class,'index'])->name('.users');
    Route::delete('/users/{user}',[UserListController::class,'delete'])->name('.users.delete');

    Route::get('/users/create',[UserListController::class,'showCreateForm'])->name('.users.showCreateForm');
    Route::post('/users/create',[UserListController::class,'create'])->name('.users.create');
});


