<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/info/server', [UserController::class, 'getPhpVersion']);
Route::get('/info/client', [UserController::class, 'getClientInfo']);
Route::get('info/database', [UserController::class, 'getDatabaseInfo']);

Route::get('/', function () {
    return view('welcome');
});
