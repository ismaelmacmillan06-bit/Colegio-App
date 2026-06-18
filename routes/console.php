<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Actualizar estatus de colegiaturas diariamente a la 1:00 AM
Schedule::command('colegiaturas:actualizar')->dailyAt('01:00');

// Generar colegiaturas al inicio de cada bimestre escolar mexicano
// Bim 1: Sep 1 · Bim 2: Nov 1 · Bim 3: Ene 1 · Bim 4: Mar 1 · Bim 5: May 1
Schedule::command('colegiaturas:actualizar --generar')
    ->monthlyOn(1, '06:00')
    ->when(fn () => in_array(now()->month, [9, 11, 1, 3, 5]));
