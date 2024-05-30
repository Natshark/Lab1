<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleAndPermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserAndRoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChangeLogsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::middleware('guest:sanctum')->post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'getUser'])->name('me');
        Route::post('/out', [AuthController::class, 'logout']);
        Route::get('/tokens', [AuthController::class, 'getTokens']);
        Route::post('/out_all', [AuthController::class, 'logoutAll']);
    });
});

Route::prefix('ref/policy/')->middleware(['auth:sanctum', 'check.permissions'])->group(function () {
    Route::prefix('role/')->group(function () {
        Route::get('', [RoleController::class, 'getAll']);
        Route::post('', [RoleController::class, 'create']);

        Route::prefix('{id}/')->group(function () {
            Route::get('', [RoleController::class, 'getById']);
            Route::put('', [RoleController::class, 'update']);
            Route::delete('', [RoleController::class, 'hardDelete']);
            Route::delete('soft', [RoleController::class, 'softDelete']);
            Route::post('restore', [RoleController::class, 'restore']);
            Route::get('story', [RoleController::class, 'getStory']);
        });
    });

    Route::prefix('permission/')->group(function () {
        Route::get('', [PermissionController::class, 'getAll']);
        Route::post('', [PermissionController::class, 'create']);

        Route::prefix('{id}/')->group(function () {
            Route::get('', [PermissionController::class, 'getById']);
            Route::put('', [PermissionController::class, 'update']);
            Route::delete('', [PermissionController::class, 'hardDelete']);
            Route::delete('soft', [PermissionController::class, 'softDelete']);
            Route::post('restore', [PermissionController::class, 'restore']);
            Route::get('story', [PermissionController::class, 'getStory']);
        });
    });

    Route::prefix('userAndRole/')->group(function () {
        Route::get('', [UserAndRoleController::class, 'getAll']);
        Route::post('', [UserAndRoleController::class, 'create']);

        Route::prefix('{id}/')->group(function () {
            Route::get('', [UserAndRoleController::class, 'getById']);
            Route::put('', [UserAndRoleController::class, 'update']);
            Route::delete('', [UserAndRoleController::class, 'hardDelete']);
            Route::delete('soft', [UserAndRoleController::class, 'softDelete']);
            Route::post('restore', [UserAndRoleController::class, 'restore']);
        });
    });

    Route::prefix('roleAndPermission/')->group(function () {
        Route::get('', [RoleAndPermissionController::class, 'getAll']);
        Route::post('', [RoleAndPermissionController::class, 'create']);

        Route::prefix('{id}/')->group(function () {
            Route::get('', [RoleAndPermissionController::class, 'getById']);
            Route::put('', [RoleAndPermissionController::class, 'update']);
            Route::delete('', [RoleAndPermissionController::class, 'hardDelete']);
            Route::delete('soft', [RoleAndPermissionController::class, 'softDelete']);
            Route::post('restore', [RoleAndPermissionController::class, 'restore']);
        });
    });

    Route::prefix('user/')->group(function () {
        Route::get('', [UserController::class, 'getAll']);
        Route::post('', [UserController::class, 'create']);

        Route::prefix('{id}/')->group(function () {
            Route::get('', [UserController::class, 'getById']);
            Route::put('', [UserController::class, 'update']);
            Route::delete('', [UserController::class, 'hardDelete']);
            Route::delete('soft', [UserController::class, 'softDelete']);
            Route::post('restore', [UserController::class, 'restore']);
            Route::get('story', [UserController::class, 'getStory']);
            Route::get('change', [UserController::class, 'change']);
        });
    });
});
