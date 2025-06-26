<?php

use App\Http\Controllers\LoginControlller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [PageController::class, 'index']);
Route::get('/sobre', [PageController::class, 'about']);
Route::get('/blog', [PostController::class, 'index'])->name('post.index');

//Authentication
Route::get('/entrar', [LoginControlller::class, 'index']);
Route::get('/cadastrar', [RegisterController::class, 'index']);

// Route::get('/post', [PostController::class, 'index'])->name('post.index');

