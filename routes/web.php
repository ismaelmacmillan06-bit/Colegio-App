<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('inicio');
Route::get('/circulares', [PublicController::class, 'circulares'])->name('circulares');
Route::get('/galeria', [PublicController::class, 'galeria'])->name('galeria');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');