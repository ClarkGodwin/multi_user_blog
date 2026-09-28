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

    Route::get('published', [ArticleController::class, 'published'])->name('article.published');
    Route::get('craft', [ArticleController::class, 'craft'])->name('article.craft');
    Route::get('archived', [ArticleController::class, 'archived'])->name('article.archived');

    Route::get('article/create',[ArticleController::class, 'create'])->name('article.create');
    Route::post('article/create', [ArticleController::class, 'store'])->name('article.store');
});

require __DIR__.'/settings.php';
