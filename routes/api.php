<?php

use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\DeviceController;
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
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store']);

    Route::prefix('/cities')->name('cities.')->group(function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::get('/{city}', [CityController::class, 'show'])->name('show');
        Route::post('/', [CityController::class, 'store'])->name('store');
        Route::patch('/{city}', [CityController::class, 'update'])->name('update');
        Route::delete('/{city}', [CityController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('/devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
        Route::post('/', [DeviceController::class, 'store'])->name('store');
        Route::patch('/{device}', [DeviceController::class, 'update'])->name('update');
        Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('destroy');
    });
});
