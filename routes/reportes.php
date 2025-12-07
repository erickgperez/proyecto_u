<?php

use App\Http\Controllers\Reportes\ReporteIngresoController;
use App\Http\Controllers\Reportes\ReporteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/reportes/ingreso/aspirantes', [ReporteIngresoController::class, 'aspirantes'])->name('reportes-ingreso-aspirantes');
    Route::post('/reportes/ingreso/aspirantes/data', [ReporteIngresoController::class, 'getDataAspirantes'])->name('reportes-ingreso-aspirantes-data');

    Route::get('/reportes/parametros1', [ReporteController::class, 'parametros1'])->name('reportes-parametros1');
});
