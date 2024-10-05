<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::post('/email/verify', [EmailVerificationNotificationController::class, 'store']);

    Route::prefix('/cities')->name('cities.')->group(function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::post('/', [CityController::class, 'store'])->name('store');
        Route::get('/{city}', [CityController::class, 'show'])->name('show');
        Route::patch('/{city}', [CityController::class, 'update'])->name('update');
        Route::delete('/{city}', [CityController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::post('/', [DeviceController::class, 'store'])->name('store');
        Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
        Route::patch('/{device}', [DeviceController::class, 'update'])->name('update');
        Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/verify')->name('verify.')->group(function () {
        Route::post('/send', [VerifyCodeController::class, 'send'])->name('send');
        Route::post('/check', [VerifyCodeController::class, 'check'])->name('check');
    });
});
