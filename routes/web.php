<?php

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('home', 'Home')->name('dashboard');

    Route::post('toAuthor', function () {
        $user = auth()->user();
        $user->role = UserRoleEnum::Author->value;
        $user->save();

        Inertia::flash('status',"Congratulations, you're now an author");

        return redirect()->back();

    })->middleware('password.confirm')->name('toAuthor');

    Route::get('articles', [ArticleController::class, 'viewArticlesCreatedByTheAuthenticatedAuthor'])->name('articles');

    Route::inertia('article/create','Articles/CreateArticleForm')->name('articleCreate');
});

require __DIR__.'/settings.php';
