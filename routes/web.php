<?php

use App\Http\Controllers\GitController;
use Illuminate\Support\Facades\Route;

Route::post('/hooks/git', [GitController::class, 'handleHook']);
