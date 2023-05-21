<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\BotDestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\NoticiaImagenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

//Auth
Auth::routes(["register" => false]);

Route::middleware(['auth'])->group(function () {
    //Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    //Usuario    
    Route::get('user/generate-token/{user}', [UserController::class, 'generateToken'])->name('user.generate-token');
    Route::apiResource('user', UserController::class);

    //Noticia
    Route::get('noticia/send/{noticia}', [NoticiaController::class, 'send'])->name('noticia.send');
    Route::apiResource('noticia', NoticiaController::class)->parameters(['noticia' => 'noticia']);

    //Noticia - Imagen
    Route::get('noticia-imagen/list/{noticia}', [NoticiaImagenController::class, 'index'])->name('noticia-imagen.index');
    Route::apiResource('noticia-imagen', NoticiaImagenController::class)->except(['index', 'update']);

    //Bot
    Route::apiResource('bot', BotController::class);

    //Bot Destination
    Route::get('bot-destination/list/{bot}', [BotDestinationController::class, 'index'])->name('bot-destination.index');
    Route::get('bot-destination/test/{bot_destination}', [BotDestinationController::class, 'test'])->name('bot-destination.test');
    Route::apiResource('bot-destination', BotDestinationController::class)->except(['index']);
});
