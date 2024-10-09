<?php

use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VerifyCodeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::prefix('/cities')->name('cities.')->group(function () {
        Route::get('/', [CityController::class, 'index']);
        Route::post('/', [CityController::class, 'store']);
        Route::get('/{city:id}', [CityController::class, 'show']);
        Route::patch('/{city:id}', [CityController::class, 'update']);
        Route::delete('/{city:id}', [CityController::class, 'destroy']);
    });
    Route::prefix('/devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index']);
        Route::post('/', [DeviceController::class, 'store']);
        Route::get('/{device:id}', [DeviceController::class, 'show']);
        Route::patch('/{device:id}', [DeviceController::class, 'update']);
        Route::delete('/{device:id}', [DeviceController::class, 'destroy']);
    });
    Route::prefix('/projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);
        Route::get('/{project:id}', [ProjectController::class, 'show']);
        Route::patch('/{project:id}', [ProjectController::class, 'update']);
        Route::delete('/{project:id}', [ProjectController::class, 'destroy']);
    });
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user:id}', [UserController::class, 'show']);
        Route::patch('/{user:id}', [UserController::class, 'update']);
        Route::delete('/{user:id}', [UserController::class, 'destroy']);
        Route::get('/translations', [UserController::class, 'translations']);
        Route::post('/translations/{translation:id}/translate', [UserController::class, 'translate']);
        Route::middleware(AdminMiddleware::class)->group(function () {
            Route::get('/{user:id}/roles', [UserController::class, 'roles']);
            Route::post('/{user:id}/set', [UserController::class, 'set']);
        });
    });
    Route::prefix('/verify')->name('verify.')->group(function () {
        Route::post('/send', [VerifyCodeController::class, 'send']);
        Route::post('/check', [VerifyCodeController::class, 'check']);
    });
    Route::prefix('/registers')->name('registers.')->group(function () {
        Route::get('/', [RegisterController::class, 'index']);
        Route::post('/', [RegisterController::class, 'store']);
        Route::get('/{register:id}', [RegisterController::class, 'show']);
        Route::patch('/{register:id}', [RegisterController::class, 'update']);
        Route::delete('/{register:id}', [RegisterController::class, 'destroy']);
    });
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::prefix('/permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index']);
            Route::post('/', [PermissionController::class, 'store']);
            Route::get('/{permission:id}', [PermissionController::class, 'show']);
            Route::patch('/{permission:id}', [PermissionController::class, 'update']);
            Route::delete('/{permission:id}', [PermissionController::class, 'destroy']);
        });
        Route::prefix('/roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{role:id}', [RoleController::class, 'show']);
            Route::patch('/{role:id}', [RoleController::class, 'update']);
            Route::delete('/{role:id}', [RoleController::class, 'destroy']);
        });
    });
});
