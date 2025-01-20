<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::apiResource('/users', App\Http\Controllers\UserController::class);
Route::post('/register', [App\Http\Controllers\UserController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});
