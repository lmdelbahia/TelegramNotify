<?php

use App\Http\Controllers\Api\ApiBotController;
use App\Http\Controllers\Api\PublicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware('auth:sanctum')->group(function () {
    //Publicaciones
    Route::post('publish-to-all', [PublicationController::class, 'toAll'])->name('publication.to-all');
    Route::post('publish-to-bots', [PublicationController::class, 'toBots'])->name('publication.to-bots');

    //Bots
    Route::apiResource('bots', ApiBotController::class)->except(['store', 'show', 'update', 'destroy']);
});
