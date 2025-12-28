<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RefreshTokenController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rotas públicas com rate limiting
Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,1'); // 3 tentativas por minuto
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1'); // 5 tentativas por minuto
Route::post('/refresh', [RefreshTokenController::class, 'refresh'])->middleware('throttle:10,1'); // 10 tentativas por minuto

// Rotas protegidas
Route::middleware('auth:api')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [LoginController::class, 'logout']);
});
