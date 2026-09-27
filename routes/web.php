<?php

use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('home', 'Home')->name('dashboard');

    Route::post('toAuthor', function () {
        $user = auth()->user();
        $user->role = UserRoleEnum::Author->value;
        $user->save();

        return redirect()->back()->with('success',"Congratulations, you're now an author");

    })->middleware('password.confirm')->name('toAuthor');

    Route::post('toReader', function () {
        $user = auth()->user();
        $user->role = UserRoleEnum::Reader->value;
        $user->save();

        Inertia::flash('success',"Congratulations, you're now an reader");

        return redirect()->back();

    })->middleware('password.confirm')->name('toReader');

    Route::inertia('articles','Articles/Articles')->name('articles');
    Route::inertia('article/create','Articles/CreateArticleForm')->name('articleCreate');
});

require __DIR__.'/settings.php';
