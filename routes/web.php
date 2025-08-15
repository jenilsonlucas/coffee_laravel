<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AppInvoiceController;
use App\Http\Controllers\LoginControlller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/',  [PageController::class, 'index']);
Route::get('/sobre', [PageController::class, 'about']);

//blog routes
Route::get('/blog', [PostController::class, 'index'])->name('post.index');
Route::post('/blog/buscar', [PostController::class, 'postSearch']);
Route::get('/blog/{post:uri}', [PostController::class, 'show'])->name('post.show');

//Authentication
Route::get('/entrar', [LoginControlller::class, 'index']);
Route::post('/entrar', [LoginControlller::class, 'login']);
Route::get('/cadastrar', [RegisterController::class, 'index']);
Route::post('/cadastrar', [RegisterController::class, 'register']);

Route::middleware('auth')->group(function() {
    //verification email
    Route::get('/email/verificar', function () {
        return view('optin.optin-confirm');
    })->name('verification.notice');

    Route::get('/email/verificar/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/app');
    })->middleware('signed')->name('verification.verify');
});


Route::prefix('app')->group(function () {
 
    Route::get("/", [AppController::class, "home"]);
    Route::get("/sair", [LoginControlller::class, "logout"]);

    Route::post('/launch', [AppInvoiceController::class, 'launch'])
    ->middleware('throttle:applaunch');
});

Route::get("/test", [AppController::class, "test"]);
