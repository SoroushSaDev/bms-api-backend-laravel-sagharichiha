<?php

use App\Http\Controllers\AugmentedRealityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubProjectController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyCodeController;
use App\Http\Controllers\VirtualRealityController;
use App\Http\Controllers\VoiceTraceController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);
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
            Route::get('/{device:id}/registers', [DeviceController::class, 'registers']);
        });
        Route::prefix('/projects')->name('projects.')->group(function () {
            Route::get('/types', [ProjectController::class, 'types'])->name('types');
            Route::get('/', [ProjectController::class, 'index'])->name('index');
            Route::post('/', [ProjectController::class, 'store'])->name('store');
            Route::get('/{project:id}', [ProjectController::class, 'show'])->name('show');
            Route::patch('/{project:id}', [ProjectController::class, 'update'])->name('update');
            Route::delete('/{project:id}', [ProjectController::class, 'destroy'])->name('destroy');
            Route::prefix('/{project:id}/subs')->name('subs.')->group(function () {
                Route::get('/', [SubProjectController::class, 'index'])->name('index');
                Route::post('/', [SubProjectController::class, 'store'])->name('store');
                Route::get('/{sub}', [SubProjectController::class, 'show'])->name('show');
                Route::patch('/{sub}', [SubProjectController::class, 'update'])->name('update');
                Route::delete('/{sub}', [SubProjectController::class, 'destroy'])->name('destroy');
                Route::get('/{sub}/scan', [SubProjectController::class, 'scan'])->name('scan');
            });
        });
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/languages', [UserController::class, 'languages']);
            Route::get('/timezones', [UserController::class, 'timezones']);
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
            Route::get('/test', [RegisterController::class, 'test']);
            Route::get('/', [RegisterController::class, 'index']);
            Route::post('/', [RegisterController::class, 'store']);
            Route::get('/{register:id}', [RegisterController::class, 'show']);
            Route::patch('/{register:id}', [RegisterController::class, 'update']);
            Route::delete('/{register:id}', [RegisterController::class, 'destroy']);
            Route::post('/{register:id}/logs', [RegisterController::class, 'logs']);
        });
        Route::prefix('/components')->name('components.')->group(function () {
            Route::get('/', [ComponentController::class, 'index']);
            Route::post('/', [ComponentController::class, 'store']);
            Route::get('/{component:id}', [ComponentController::class, 'show']);
            Route::patch('/{component:id}', [ComponentController::class, 'update']);
            Route::delete('/{component:id}', [ComponentController::class, 'destroy']);
        });
        Route::prefix('/forms')->name('forms.')->group(function () {
            Route::get('/', [FormController::class, 'index']);
            Route::post('/', [FormController::class, 'store']);
            Route::get('/{form:id}', [FormController::class, 'show']);
            Route::patch('/{form:id}', [FormController::class, 'update']);
            Route::delete('/{form:id}', [FormController::class, 'destroy']);
        });
        Route::prefix('/files')->name('files.')->group(function () {
            Route::get('/', [FileController::class, 'index']);
            Route::post('/', [FileController::class, 'store']);
            Route::delete('/{file:id}', [FileController::class, 'destroy']);
        });
        Route::prefix('/categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::get('/{category:id}', [CategoryController::class, 'show']);
            Route::patch('/{category:id}', [CategoryController::class, 'update']);
            Route::delete('/{category:id}', [CategoryController::class, 'destroy']);
        });
        Route::prefix('/vr')->name('vr.')->group(function () {
            Route::prefix('/data')->name('data.')->group(function () {
                Route::get('/', [VirtualRealityController::class, 'index']);
                Route::post('/', [VirtualRealityController::class, 'store']);
                Route::get('/{vr:id}', [VirtualRealityController::class, 'show']);
                Route::patch('/{vr:id}', [VirtualRealityController::class, 'update']);
                Route::delete('/{vr:id}', [VirtualRealityController::class, 'destroy']);
            });
        });
        Route::prefix('/augmented-realities')->name('AR.')->group(function () {
            Route::get('/', [AugmentedRealityController::class, 'index']);
            Route::post('/', [AugmentedRealityController::class, 'store']);
            Route::get('/{ar:id}', [AugmentedRealityController::class, 'show']);
            Route::patch('/{ar:id}', [AugmentedRealityController::class, 'update']);
            Route::delete('/{ar:id}', [AugmentedRealityController::class, 'destroy']);
        });
        Route::prefix('/templates')->group(function () {
            Route::get('/', [TemplateController::class, 'index']);
            Route::post('/', [TemplateController::class, 'store']);
            Route::get('/{template:id}', [TemplateController::class, 'show']);
            Route::patch('/{template:id}', [TemplateController::class, 'update']);
            Route::delete('/{template:id}', [TemplateController::class, 'destroy']);
        });
        Route::prefix('/voice')->group(function () {
            Route::post('/trace', [VoiceTraceController::class, 'trace']);
        });

        Route::get('/GetConnections', [DeviceController::class, 'GetConnections']);
        Route::get('/GetCountries', [CityController::class, 'GetCountries']);

        Route::middleware(AdminMiddleware::class)->group(function () {
            Route::prefix('/permissions')->name('permissions.')->group(function () {
                Route::get('/', [PermissionController::class, 'index']);
                Route::post('/', [PermissionController::class, 'store']);
                Route::get('/{permission:id}', [PermissionController::class, 'show']);
                Route::patch('/{permission:id}', [PermissionController::class, 'update']);
                Route::delete('/{permission:id}', [PermissionController::class, 'destroy']);
            });
        });

        Route::prefix('/roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/{role:id}', [RoleController::class, 'show']);
            Route::patch('/{role:id}', [RoleController::class, 'update']);
            Route::delete('/{role:id}', [RoleController::class, 'destroy']);
        });
    });
    Route::post('/vr/upload', [FileController::class, 'VR']);
});
