<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\Api\WebPublicaController;

/*
|--------------------------------------------------------------------------
| NFC / Asistencia — uso interno (lector Python)
|--------------------------------------------------------------------------
*/
Route::post('/asistencia/registrar',   [AsistenciaController::class, 'registrar']);
Route::post('/asignar-uid',            [AsistenciaController::class, 'asignarUid']);
Route::get('/buscar-uid/{uid}',        [AsistenciaController::class, 'buscarUid']);
Route::get('/buscar-persona',          [AsistenciaController::class, 'buscarPersona']);
Route::post('/leer-nfc-y-asignar',     [AsistenciaController::class, 'leerNfcYAsignar']);
Route::get('/ultimo-registro',         [AsistenciaController::class, 'ultimoRegistro']);

/*
|--------------------------------------------------------------------------
| Web Pública — consumida por el sitio web separado
|--------------------------------------------------------------------------
*/
Route::prefix('web')->group(function () {
    Route::get('/sliders',      [WebPublicaController::class, 'sliders']);
    Route::get('/menu',         [WebPublicaController::class, 'menu']);
    Route::get('/circulares',   [WebPublicaController::class, 'circulares']);
    Route::get('/galeria',      [WebPublicaController::class, 'galeria']);
    Route::get('/cuadro-honor', [WebPublicaController::class, 'cuadroHonor']);
});
