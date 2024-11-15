<?php

use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});


Route::get('/register',[RegisterUserController::class,'index']);
Route::post('/register',[RegisterUserController::class,'store']);

Route::post('/login', [SessionController::class, 'store']);
