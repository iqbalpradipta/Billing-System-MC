<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', [App\Http\Controllers\UserController::class, 'GetDataUsers']);
Route::post('/register', [App\Http\Controllers\UserController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\UserController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->name('logout');

Route::get('/wallet', [App\Http\Controllers\WalletController::class, 'GetDataWallet']);
Route::post('/wallet', [App\Http\Controllers\WalletController::class, 'CreateWallet']);
Route::put('/wallet', [App\Http\Controllers\WalletController::class, 'UpdateWallet']);

Route::middleware('auth:api')->get('/transaction', [App\Http\Controllers\TransactionController::class, 'GetDataTransaction']);
Route::middleware('auth:api')->post('/transaction', [App\Http\Controllers\TransactionController::class, 'CreateTransaction']);

Route::apiResource('/vps', App\Http\Controllers\VpsController::class);
