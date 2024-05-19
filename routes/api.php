<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserController::class, 'login'])->name('login');
    Route::middleware('guest:sanctum')->post('/register', [UserController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [UserController::class, 'getUser'])->name('me');
        Route::post('/out', [UserController::class, 'logout']);
        Route::get('/tokens', [UserController::class, 'getTokens']);
        Route::post('/out_all', [UserController::class, 'logoutAll']);
    });
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
