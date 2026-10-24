<?php

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
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

    Route::get('article/detail/{article}',[ArticleController::class, 'details'])->name('article.details');

    Route::get('article/edit/{article}',[ArticleController::class, 'edit'])->name('article.edit');
    Route::post('article/update/{article}',[ArticleController::class, 'update'])->name('article.update');
    Route::delete('article/destroy/{article}',[ArticleController::class, 'destroy'])->name('article.destroy');




    Route::get('comment/detail/{comment}',[CommentController::class, 'details'])->name('comment.details');
    Route::post('comment/store/{article}',[CommentController::class, 'store'])->name('comment.store');
    Route::post('comment/update/{comment}',[CommentController::class, 'update'])->name('comment.update');
    Route::delete('comment/destroy/{comment}',[CommentController::class, 'destroy'])->name('comment.destroy');

});

require __DIR__.'/settings.php';
