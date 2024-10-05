<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
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
        Route::get('/{city:id}', [CityController::class, 'show'])->name('show');
        Route::patch('/{city:id}', [CityController::class, 'update'])->name('update');
        Route::delete('/{city:id}', [CityController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::post('/', [DeviceController::class, 'store'])->name('store');
        Route::get('/{device:id}', [DeviceController::class, 'show'])->name('show');
        Route::patch('/{device:id}', [DeviceController::class, 'update'])->name('update');
        Route::delete('/{device:id}', [DeviceController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project:id}', [ProjectController::class, 'show'])->name('show');
        Route::patch('/{project:id}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project:id}', [ProjectController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user:id}', [UserController::class, 'show'])->name('show');
        Route::patch('/{user:id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user:id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/verify')->name('verify.')->group(function () {
        Route::post('/send', [VerifyCodeController::class, 'send'])->name('send');
        Route::post('/check', [VerifyCodeController::class, 'check'])->name('check');
    });

    Route::prefix('/registers')->name('registers.')->group(function () {
        Route::get('/', [RegisterController::class, 'index'])->name('index');
        Route::post('/', [RegisterController::class, 'store'])->name('store');
        Route::get('/{register:id}', [RegisterController::class, 'show'])->name('show');
        Route::patch('/{register:id}', [RegisterController::class, 'update'])->name('update');
        Route::delete('/{register:id}', [RegisterController::class, 'destroy'])->name('destroy');
    });
});
