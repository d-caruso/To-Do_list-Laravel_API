<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function() {
    
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

    Route::put('/user/password', [UserController::class, 'updatePassword'])
    ->name('password.store');

    Route::get('/user', function(Request $request) {
        return $request->user();
    })
    ->name('user');

    Route::post('/todos', [TodoController::class, 'store'])
    ->name('todo.store');

    Route::get('/todos', [TodoController::class, 'index'])
    ->name('todo.index');

    Route::put('/todos/{todo}', [TodoController::class, 'update'])
    ->name('todo.update');

});

Route::middleware(['guest'])->group(function() {
    
    Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

});

//Route::apiResource('user', PostController::class)->middleware('auth:sanctum');