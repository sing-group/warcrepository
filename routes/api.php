<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\CorpusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Mismas rutas que la API v1 de Dingo (prefijo /api), sin cambios para
| los clientes externos.
|
*/

Route::post('login', [AuthenticateController::class, 'authenticate']);
Route::post('user', [UserController::class, 'create']);
Route::get('token', [AuthenticateController::class, 'getToken']);

Route::middleware('api.auth')->group(function () {
    Route::get('users', [UserController::class, 'users']);
    Route::get('me', [UserController::class, 'me']);
    Route::post('corpus', [CorpusController::class, 'store']);
});
