<?php

use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('home', 'Home')->name('dashboard');

    Route::post('toAuthor', function () {
        $user = auth()->user();
        $user->role = UserRoleEnum::Author->value;
        $user->save();

        return redirect()->back()->with('success',"Congratulations, you're now an author");

    })->middleware('password.confirm')->name('toAuthor');

    Route::inertia('articles','Articles/Articles')->name('articles');
    Route::inertia('article/create','Articles/CreateArticleForm')->name('articleCreate');
});

require __DIR__.'/settings.php';
