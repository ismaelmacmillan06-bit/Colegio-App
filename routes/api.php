<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;

Route::post('/asistencia/registrar', [AsistenciaController::class, 'registrar']);