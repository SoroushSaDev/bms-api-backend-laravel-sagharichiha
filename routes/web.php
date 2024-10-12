<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

//require __DIR__.'/auth.php';

Route::get('/', function () {
    $route = auth()->check() ? route('devices.index') : route('login');
    return redirect($route);
});
Route::get('/login', [HomeController::class, 'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::prefix('/cities')->name('cities.')->group(function () {
        Route::get('/', [CityController::class, 'index'])->name('index');
        Route::post('/', [CityController::class, 'store'])->name('store');
        Route::get('/create', [CityController::class, 'create'])->name('create');
        Route::get('/{city:id}', [CityController::class, 'show'])->name('show');
        Route::get('/{city:id}/edit', [CityController::class, 'edit'])->name('edit');
        Route::patch('/{city:id}', [CityController::class, 'update'])->name('update');
        Route::delete('/{city:id}', [CityController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('/projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::get('/{project:id}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project:id}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::patch('/{project:id}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project:id}', [ProjectController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/{user:id}', [UserController::class, 'show'])->name('show');
        Route::get('/{user:id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{user:id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user:id}', [UserController::class, 'destroy'])->name('destroy');
    });
    Route::resource('translations', TranslationController::class);
    Route::prefix('/devices/{device:id}')->name('registers.')->group(function () {
        Route::post('/', [RegisterController::class, 'store'])->name('store');
        Route::get('/create', [RegisterController::class, 'create'])->name('create');
    });
    Route::prefix('/registers')->name('registers.')->group(function () {
        Route::get('/{register:id}', [RegisterController::class, 'show'])->name('show');
        Route::get('/{register:id}/edit', [RegisterController::class, 'edit'])->name('edit');
        Route::patch('/{register:id}', [RegisterController::class, 'update'])->name('update');
        Route::delete('/{register:id}', [RegisterController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('/devices')->name('devices.')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('index');
        Route::post('/', [DeviceController::class, 'store'])->name('store');
        Route::get('/create', [DeviceController::class, 'create'])->name('create');
        Route::prefix('/{device:id}')->group(function () {
            Route::get('/', [DeviceController::class, 'show'])->name('show');
            Route::get('/edit', [DeviceController::class, 'edit'])->name('edit');
            Route::patch('/', [DeviceController::class, 'update'])->name('update');
            Route::delete('/', [DeviceController::class, 'destroy'])->name('destroy');
            Route::get('/registers', [RegisterController::class, 'index'])->name('registers');
        });
    });
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::prefix('/permissions')->name('permissions.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::post('/', [PermissionController::class, 'store'])->name('store');
            Route::get('/create', [PermissionController::class, 'create'])->name('create');
            Route::get('/{permission:id}', [PermissionController::class, 'show'])->name('show');
            Route::get('/{permission:id}/edit', [PermissionController::class, 'edit'])->name('edit');
            Route::patch('/{permission:id}', [PermissionController::class, 'update'])->name('update');
            Route::delete('/{permission:id}', [PermissionController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('/roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::get('/{role:id}', [RoleController::class, 'show'])->name('show');
            Route::get('/{role:id}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::patch('/{role:id}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role:id}', [RoleController::class, 'destroy'])->name('destroy');
        });
    });
});
