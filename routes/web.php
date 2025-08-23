<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AppInvoiceController;
use App\Http\Controllers\LoginControlller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use Dom\Attr;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Predis\Command\Redis\APPEND;

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
    Route::get("/receber/{status?}/{category?}/{date?}", [AppInvoiceController::class, "income"]);
    Route::get("/pagar/{status?}/{category?}/{date?}", [AppInvoiceController::class, "expense"]);
    Route::get("/fixas", [AppInvoiceController::class, "fixas"]);
    Route::get("/fatura/{invoice}", [AppInvoiceController::class, "invoice"]);
    Route::get("/perfil", [UserController::class, "edit"]);
    Route::get("/sair", [LoginControlller::class, "logout"]);

    Route::post('/launch', [AppInvoiceController::class, 'launch'])
    ->middleware('throttle:applaunch');
    Route::put("/onpaid", [AppInvoiceController::class, 'onpaid']);
    Route::put("/invoice/{invoice}", [AppInvoiceController::class, "update"]);
    Route::delete("/remove/{invoice}", [AppInvoiceController::class, "destroy"]);
    Route::post("/filter", [AppController::class, "filter"]);
    Route::put("/profile", [UserController::class, "profile"]);

});

