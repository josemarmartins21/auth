<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\ap\Http\Controllers\UserController;
use Modules\Auth\app\Http\Controllers\AuthController;
use Modules\Auth\app\Models\Permission;
use Modules\Auth\app\Models\Role;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('auths', AuthController::class)->names('auth');
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('roles', function () {
        return Role::all();
    });
    
    Route::get('permissions', function () {
        $permissionsRole = [];

        foreach (Role::all() as $role) {
           $permissionsRole[$role->name] = $role->permissions->pluck('name');
        }

        return response()->json([
            'data' => $permissionsRole,
        ]);
    });

    Route::get('users', [UserController::class, 'index'])->middleware('can:view_user');

});
