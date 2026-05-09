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

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\DashboardController::class)->except(['create', 'edit', 'show']);
    Route::view('/banner', 'admin.banner')->name('banner.index');
});
