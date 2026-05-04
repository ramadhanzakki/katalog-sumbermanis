<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.index', [
        'products'   => collect(),
        'categories' => collect(),
        'banners'    => collect(),
    ]);
})->name('user.index');


