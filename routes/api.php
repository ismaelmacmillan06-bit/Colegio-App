<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;

Route::post('/asistencia/registrar', [AsistenciaController::class, 'registrar']);
Route::post('/asistencia/registrar', [App\Http\Controllers\AsistenciaController::class, 'registrar']);
Route::post('/asignar-uid', [App\Http\Controllers\AsistenciaController::class, 'asignarUid']);
Route::get('/buscar-uid/{uid}', [App\Http\Controllers\AsistenciaController::class, 'buscarUid']);