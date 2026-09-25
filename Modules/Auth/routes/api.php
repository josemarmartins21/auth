<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\AuthController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('auths', AuthController::class)->names('auth');
});

Route::post('login', [AuthController::class, 'login']);

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
