<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$currentVersion = 'v1';

//auth endpoints
Route::
    prefix($currentVersion)->
    middleware(['auth:sanctum'])->
    group(function() {
    
    Route::delete('/auths', [AuthenticatedSessionController::class, 'destroy'])
         ->name('auth.destroy');

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

    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])
         ->name('todo.destroy');

});

//guest endpoints
Route::
    prefix($currentVersion)->
    middleware(['guest'])->
    group(function() {
    
    Route::post('/users', [RegisteredUserController::class, 'store'])
         ->name('users.store');

    Route::post('/auths', [AuthenticatedSessionController::class, 'store'])
         ->name('auth.store');

});