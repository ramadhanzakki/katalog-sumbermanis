<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', [UserController::class, 'index'])->name('user.index');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
