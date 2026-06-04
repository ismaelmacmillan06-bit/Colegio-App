<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\CredencialController;

Route::get('/', [PublicController::class, 'index'])->name('inicio');
Route::get('/circulares', [PublicController::class, 'circulares'])->name('circulares');
Route::get('/galeria', [PublicController::class, 'galeria'])->name('galeria');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/admin-importar/{tipo}/plantilla', [App\Http\Controllers\ImportacionController::class, 'descargarPlantilla'])->name('importar.plantilla');
Route::post('/admin-importar/{tipo}', [App\Http\Controllers\ImportacionController::class, 'importar'])->name('importar.csv');
Route::get('/bienvenida', [PublicController::class, 'bienvenida'])->name('bienvenida');

Route::middleware('auth')->group(function () {
    Route::get('/credencial/alumno/{alumno}', [CredencialController::class, 'alumno'])
        ->name('credencial.alumno');
});