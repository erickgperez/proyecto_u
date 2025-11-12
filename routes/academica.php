<?php

use App\Http\Controllers\Academica\AreaController;
use App\Http\Controllers\Academica\CarreraController;
use App\Http\Controllers\Academica\SedeController;
use App\Http\Controllers\Academica\GradoController;
use App\Http\Controllers\Academica\MallaCurricularController;
use App\Http\Controllers\Academica\TipoCarreraController;
use App\Http\Controllers\Academica\TipoRequisitoController;
use App\Http\Controllers\Academica\TipoUnidadAcademicaController;
use App\Http\Controllers\Academica\UnidadAcademicaController;
use App\Models\PlanEstudio\TipoRequisito;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/academica/sede', [SedeController::class, 'index'])->name('academica-sede-index');
    Route::post('/academica/sede/save', [SedeController::class, 'save'])->name('academica-sede-save');
    Route::delete('/academica/sede/{id}/delete', [SedeController::class, 'delete'])->name('academica-sede-delete');

    Route::get('/plan_estudio/grado', [GradoController::class, 'index'])->name('plan_estudio-grado-index');
    Route::post('/plan_estudio/grado/save', [GradoController::class, 'save'])->name('plan_estudio-grado-save');
    Route::delete('/plan_estudio/grado/{id}/delete', [GradoController::class, 'delete'])->name('plan_estudio-grado-delete');

    Route::get('/plan_estudio/tipo_carrera', [TipoCarreraController::class, 'index'])->name('plan_estudio-tipo_carrera-index');
    Route::post('/plan_estudio/tipo_carrera/save', [TipoCarreraController::class, 'save'])->name('plan_estudio-tipo_carrera-save');
    Route::delete('/plan_estudio/tipo_carrera/{id}/delete', [TipoCarreraController::class, 'delete'])->name('plan_estudio-tipo_carrera-delete');

    Route::get('/plan_estudio/carrera', [CarreraController::class, 'index'])->name('plan_estudio-carrera-index');
    Route::post('/plan_estudio/carrera/save', [CarreraController::class, 'save'])->name('plan_estudio-carrera-save');
    Route::delete('/plan_estudio/carrera/{id}/delete', [CarreraController::class, 'delete'])->name('plan_estudio-carrera-delete');

    Route::get('/plan_estudio/area', [AreaController::class, 'index'])->name('plan_estudio-area-index');
    Route::post('/plan_estudio/area/save', [AreaController::class, 'save'])->name('plan_estudio-area-save');
    Route::delete('/plan_estudio/area/{id}/delete', [AreaController::class, 'delete'])->name('plan_estudio-area-delete');

    Route::get('/plan_estudio/tipo-tipo-requisito', [TipoRequisitoController::class, 'index'])->name('plan_estudio-tipo_requisito-index');
    Route::post('/plan_estudio/tipo-tipo-requisito/save', [TipoRequisitoController::class, 'save'])->name('plan_estudio-tipo_requisito-save');
    Route::delete('/plan_estudio/tipo-tipo-requisito/{id}/delete', [TipoRequisitoController::class, 'delete'])->name('plan_estudio-tipo_requisito-delete');

    Route::get('/plan_estudio/tipo-unidad-academica', [TipoUnidadAcademicaController::class, 'index'])->name('plan_estudio-tipo_unidad_academica-index');
    Route::post('/plan_estudio/tipo-unidad-academica/save', [TipoUnidadAcademicaController::class, 'save'])->name('plan_estudio-tipo_unidad_academica-save');
    Route::delete('/plan_estudio/tipo-unidad-academica/{id}/delete', [TipoUnidadAcademicaController::class, 'delete'])->name('plan_estudio-tipo_unidad_academica-delete');

    Route::get('/plan_estudio/unidad-academica', [UnidadAcademicaController::class, 'index'])->name('plan_estudio-unidad_academica-index');
    Route::post('/plan_estudio/unidad-academica/save', [UnidadAcademicaController::class, 'save'])->name('plan_estudio-unidad_academica-save');
    Route::delete('/plan_estudio/unidad-academica/{id}/delete', [UnidadAcademicaController::class, 'delete'])->name('plan_estudio-unidad_academica-delete');

    Route::get('/plan_estudio/malla-curricular', [MallaCurricularController::class, 'index'])->name('plan_estudio-malla_curricular-index');
    Route::post('/plan_estudio/malla-curricular/save', [MallaCurricularController::class, 'save'])->name('plan_estudio-malla_curricular-save');
    Route::delete('/plan_estudio/malla-curricular/{id}/delete', [MallaCurricularController::class, 'delete'])->name('plan_estudio-malla_curricular-delete');
});
