<?php

use App\Http\Controllers\LoginControlller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/',  [PageController::class, 'index']);
Route::get('/sobre', [PageController::class, 'about']);

//blog routes
Route::get('/blog', [PostController::class, 'index'])->name('post.index');
Route::get('/blog/{post:uri}', [PostController::class, 'show'])->name('post.show');


//Authentication
Route::get('/entrar', [LoginControlller::class, 'index']);
Route::post('/entrar', [LoginControlller::class, 'login']);
Route::get('/cadastrar', [RegisterController::class, 'index']);



// Route::get('/post', [PostController::class, 'index'])->name('post.index');
