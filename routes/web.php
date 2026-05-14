<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('inicio');
Route::get('/circulares', [PublicController::class, 'circulares'])->name('circulares');
Route::get('/galeria', [PublicController::class, 'galeria'])->name('galeria');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/admin-importar/{tipo}/plantilla', [App\Http\Controllers\ImportacionController::class, 'descargarPlantilla'])->name('importar.plantilla');
Route::post('/admin-importar/{tipo}', [App\Http\Controllers\ImportacionController::class, 'importar'])->name('importar.csv');