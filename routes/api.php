<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'read']);
Route::get('/post/search', [PostController::class, 'search'])->name('search');

Route::delete('/post/{id}', [PostController::class, 'delete'])->name('post.delete');
Route::get('/post/mysearch', [UserController::class, 'search'])->name('mysearch');
Route::get('/myposts', [UserController::class, 'userPosts'])->name('userPosts');
Route::post('/publish', [PostController::class, 'create'])->name('post.store');
Route::post('/post/{id}', [PostController::class, 'update'])->name('post.update');
