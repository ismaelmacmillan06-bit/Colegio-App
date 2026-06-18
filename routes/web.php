<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\IdEscolarController;

Route::get('/', fn () => redirect('/admin'));

Route::get('/admin-importar/{tipo}/plantilla', [App\Http\Controllers\ImportacionController::class, 'descargarPlantilla'])->name('importar.plantilla');
Route::post('/admin-importar/{tipo}', [App\Http\Controllers\ImportacionController::class, 'importar'])->name('importar.csv');

Route::middleware(['auth', \App\Http\Middleware\EnsureColegioAdmin::class])->group(function () {
    Route::get('/credencial/alumno/{alumno}', [CredencialController::class, 'alumno'])
        ->name('credencial.alumno');

    Route::get('/admin/id-escolar/{claseId}/pdf', [IdEscolarController::class, 'pdf'])
        ->name('id-escolar.pdf');
});