<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\app\Http\Controllers\UserController;
use Modules\Auth\app\Http\Controllers\AuthController;
use Modules\Auth\app\Http\Controllers\RoleController;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::get('users', [UserController::class, 'index']);
    Route::middleware('permission:ver usuário,api')->get('users/{id}', [UserController::class, 'show']);

    Route::middleware(['role:admin, api', 'permission:criar usuário,api'])->post('users', [UserController::class, 'store']);

    Route::put('users/{id}', [UserController::class, 'update']);   
    
    Route::middleware(['role:admin,api', 'permission:excluir usuário,api'])->delete('users/{id}', [UserController::class, 'destroy']);
    
    Route::get('permissions', [RoleController::class, 'index']);

    Route::middleware('role:manager,api')->group(function() {
        Route::post('give-permission/{id}', [RoleController::class, 'givePermission']);
        Route::post('remove-permission/{id}', [RoleController::class, 'removePermission']);
    });

});
