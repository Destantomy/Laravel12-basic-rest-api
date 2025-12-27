<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\UserController;
// use  App\Http\Controllers\ArticleController;

// public route for user log-no need auth
Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::get('/users', [UserController::class, 'getAllUsers'])
    ->middleware('auth:sanctum');

// protected routes for articles
Route::middleware('auth:sanctum')->group(function() {
    // users
    Route::prefix('users')->group(function () {
        Route::get('/me', [UserController::class, 'getUser']);
        Route::patch('/me', [UserController::class, 'updateUserById']);
    });
});