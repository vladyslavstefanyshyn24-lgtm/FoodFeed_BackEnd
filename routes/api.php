<?php

use App\Http\Controllers\DishController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/feed', [FeedController::class, 'index']);

    Route::get('/dishes', [DishController::class, 'index']);
    Route::get('/dishes/{id}', [DishController::class, 'show']);
    Route::post('/dishes', [DishController::class, 'store']);
    Route::put('/dishes/{id}', [DishController::class, 'update']);
    Route::delete('/dishes/{id}', [DishController::class, 'destroy']);

    Route::post('/ratings', [RatingController::class, 'store']);
    Route::put('/ratings/{id}', [RatingController::class, 'update']);
    Route::get('/ratings/{dishId}/stats', [RatingController::class, 'stats']);
    
    Route::get('/profile/{id}', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
