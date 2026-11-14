<?php

use App\Http\Controllers\AtraccionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SedeController;
use App\Models\Atraccion;
use App\Models\Sede;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'sedes'       => Sede::withCount('atracciones')->orderBy('nombre')->get(),
        'atracciones' => Atraccion::with('sede')->latest()->take(6)->get(),
    ]);
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/reportes/atracciones', [ReporteController::class, 'atracciones'])
        ->name('reportes.atracciones');

    Route::get('/consola-api', function () {
        return view('consola-api');
    })->name('consola-api');

    Route::resource('sedes', SedeController::class)->except('show');
    Route::resource('atracciones', AtraccionController::class)
        ->parameters(['atracciones' => 'atraccion'])
        ->except('show');
});

require __DIR__.'/auth.php';
