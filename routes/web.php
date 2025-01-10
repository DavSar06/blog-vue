<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home');
})->name('dashboard');


Route::get('/post/{id}', function () {
    return view('post');
})->name('post');

Route::middleware('auth')->group(function () {
    Route::get('/post/{id}/edit', function () {
        return view('edit');
    })->name('post.edit');

    Route::get('/mypage', function () {
        return view('usersPosts');
    })->name('mypage');

    Route::get('/posts/create', function () {
        return view('create');
    })->name('post.create');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
