<?php

use App\Http\Controllers\Reportes\ReporteAsignaturaController;
use App\Http\Controllers\Reportes\ReporteIngresoController;
use App\Http\Controllers\Reportes\ReporteController;
use App\Http\Controllers\Reportes\ReporteEstudiantesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/reportes/ingreso/aspirantes', [ReporteIngresoController::class, 'aspirantes'])->name('reportes-ingreso-aspirantes');
    Route::post('/reportes/ingreso/aspirantes/data', [ReporteIngresoController::class, 'getDataAspirantes'])->name('reportes-ingreso-aspirantes-data');

    Route::get('/reportes/estudiantes/inscritos', [ReporteEstudiantesController::class, 'inscritos'])->name('reportes-estudiantes-inscritos');
    Route::post('/reportes/estudiantes/inscritos/data', [ReporteEstudiantesController::class, 'getDataInscritos'])->name('reportes-estudiantes-inscritos-data');

    Route::get('/reportes/parametros1', [ReporteController::class, 'parametros1'])->name('reportes-parametros1');

    Route::get('/reportes/parametros/semestre', [ReporteController::class, 'parametrosSemestre'])->name('reportes-parametros-semestre');

    Route::get('/reportes/asignatura/inscritos/titular/{uuid}', [ReporteAsignaturaController::class, 'inscritosTitular'])->name('reportes-asignatura-inscritos-titular');
    Route::get('/reportes/asignatura/inscritos/asociado/{uuid}', [ReporteAsignaturaController::class, 'inscritosAsociado'])->name('reportes-asignatura-inscritos-asociado');
});
