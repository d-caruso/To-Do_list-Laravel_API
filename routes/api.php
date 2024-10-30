<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function() {
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

    Route::get('/user', function(Request $request) {
        return $request->user();
    })
    ->name('user');

});

Route::middleware(['guest'])->group(function() {
    
    Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

});

//Route::apiResource('user', PostController::class)->middleware('auth:sanctum');