<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('info')->group(function () {
    Route::get('/server', [UserController::class, 'getPhpVersion']);
    Route::get('/client', [UserController::class, 'getClientInfo']);
    Route::get('/database', [UserController::class, 'getDatabaseInfo']);
});
