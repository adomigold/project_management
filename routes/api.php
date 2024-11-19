<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/register', [AuthenticationController::class, 'register']);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::group(['middleware' => ['role:Admin']], function () {
        // Roles routes
        Route::get('/roles', [RolesController::class, 'index']);
        Route::post('/roles', [RolesController::class, 'store']);
        Route::get('/roles/{role}', [RolesController::class, 'show']);
        Route::put('/roles/{role}', [RolesController::class, 'update']);
        Route::delete('/roles/{role}', [RolesController::class, 'destroy']);

        // Assign Permission routes
        Route::post('/roles/{role}/assign-permissions', [RolesController::class, 'assignPermission']);
        Route::post('/roles/{role}/remove-permissions', [RolesController::class, 'removePermission']);

        // Users routes
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole']);

        // Permissions routes
        Route::get('/permissions', [PermissionsController::class, 'index']);
        Route::post('/permissions', [PermissionsController::class, 'store']);
        Route::get('/permissions/{permission}', [PermissionsController::class, 'show']);
        Route::put('/permissions/{permission}', [PermissionsController::class, 'update']);
        Route::delete('/permissions/{permission}', [PermissionsController::class, 'destroy']);
    });

    Route::group(['middleware' => ['role:Admin|Manager']], function () {
        // Projects routes
        Route::get('/projects', [ProjectsController::class, 'index']);
        Route::post('/projects', [ProjectsController::class, 'store']);
        Route::get('/projects/{project}', [ProjectsController::class, 'show']);
        Route::put('/projects/{project}', [ProjectsController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectsController::class, 'destroy']);

        // Tasks routes
        Route::post('projects/{project}/tasks', [TaskController::class, 'store']);
        Route::put('projects/{project}/tasks/{task}', [TaskController::class, 'update']);
        Route::delete('projects/{project}/tasks/{task}', [TaskController::class, 'destroy']);
    });

    // Tasks routes
    Route::get('projects/{project}/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus']);

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);
});
