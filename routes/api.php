<?php

use App\Http\Controllers\Api\AtraccionApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SedeApiController;
use Illuminate\Support\Facades\Route;

// ---------- Rutas públicas ----------
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('api.login');

Route::get('/atracciones', [AtraccionApiController::class, 'index'])->name('api.atracciones.index');
Route::get('/atracciones/{atraccion}', [AtraccionApiController::class, 'show'])->name('api.atracciones.show');

// ---------- Rutas protegidas con token (Sanctum) ----------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::apiResource('sedes', SedeApiController::class)->names('api.sedes');

    Route::apiResource('atracciones', AtraccionApiController::class)
        ->parameters(['atracciones' => 'atraccion'])
        ->except(['index', 'show'])
        ->names('api.atracciones');
});
