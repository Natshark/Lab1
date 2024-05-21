<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\appController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [appController::class, 'login'])->name('login');
    Route::middleware('guest:sanctum')->post('/register', [appController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [appController::class, 'getUser'])->name('me');
        Route::post('/out', [appController::class, 'logout']);
        Route::get('/tokens', [appController::class, 'getTokens']);
        Route::post('/out_all', [appController::class, 'logoutAll']);
    });
});
