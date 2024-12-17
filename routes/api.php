<?php

use App\Http\Controllers\Admin\ImportStatusController;
use App\Http\Controllers\Api\Admin\PermissionManagementController;
use App\Http\Controllers\Api\Admin\UsersManagementController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\User\AccessHistoryController;
use App\Http\Controllers\Api\User\InformationController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout')->middleware('auth:sanctum');

Route::prefix('/user')->name('api.user')->middleware(['auth:sanctum'])->group(function () {
    Route::middleware('api.check.permission')->group(function () {
        Route::get('/', [InformationController::class, 'getInformation']);
        Route::put('/', [InformationController::class, 'update'])->name('.update');
        Route::put('/password', [InformationController::class, 'updatePassword'])->name('.updatePassword');
    });

    Route::post('/email', [InformationController::class, 'sendChangeEmail'])->name('.sendChangeEmail');
    Route::post('/email/{token}', [InformationController::class, 'verifyChangeEmail'])->name('.verifyChangeEmail');

    Route::get('/access-history', [AccessHistoryController::class, 'index'])->name('.accessHistory');
});

Route::prefix('/admin')->middleware(['auth:sanctum', 'api.check.permission'])->name('api.admin')->group(function () {
    Route::get('/users', [UsersManagementController::class, 'getUsers'])->name('.users');
    Route::post('/users/import', [UsersManagementController::class, 'import'])->name('.users.import');
    Route::get('/users/export', [UsersManagementController::class, 'export'])->name('.users.export');

    Route::delete('/user/{user}', [UsersManagementController::class, 'delete'])->name('.user.delete')->where(['user' => '[0-9]+']);
    Route::post('/user/create', [UsersManagementController::class, 'create'])->name('.user.create');
    Route::get('/user/{user}', [UsersManagementController::class, 'get'])->name('.user.get')->where(['user' => '[0-9]+']);
    Route::put('/user/{user}', [UsersManagementController::class, 'update'])->name('.user.update');

    Route::get('/permissions', [PermissionManagementController::class, 'index'])->name('.permissions');
    Route::patch('/permissions', [PermissionManagementController::class, 'update'])->name('.permissions.update');

    Route::get('/import-status', [ImportStatusController::class, 'getImportStatusWithPagination'])->name('.importStatus');
});
