<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('home', 'Home')->name('dashboard');
    Route::inertia('article','Articles/Article')->name('article');
});

require __DIR__.'/settings.php';
