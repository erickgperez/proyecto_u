<?php

use App\Http\Controllers\Academico\AreaController;
use App\Http\Controllers\Academico\CarreraController;
use App\Http\Controllers\Academico\SedeController;
use App\Http\Controllers\Academico\GradoController;
use App\Http\Controllers\Academico\MallaCurricularController;
use App\Http\Controllers\Academico\SemestreController;
use App\Http\Controllers\Academico\TipoCarreraController;
use App\Http\Controllers\Academico\TipoRequisitoController;
use App\Http\Controllers\Academico\TipoUnidadAcademicaController;
use App\Http\Controllers\Academico\UnidadAcademicaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/academico/sede', [SedeController::class, 'index'])->name('academico-sede-index');
    Route::post('/academico/sede/save', [SedeController::class, 'save'])->name('academico-sede-save');
    Route::delete('/academico/sede/{id}/delete', [SedeController::class, 'delete'])->name('academico-sede-delete');

    Route::get('/academico/plan_estudio/grado', [GradoController::class, 'index'])->name('academico-plan_estudio-grado-index');
    Route::post('/academico/plan_estudio/grado/save', [GradoController::class, 'save'])->name('academico-plan_estudio-grado-save');
    Route::delete('/academico/plan_estudio/grado/{id}/delete', [GradoController::class, 'delete'])->name('academico-plan_estudio-grado-delete');

    Route::get('/academico/plan_estudio/tipo_carrera', [TipoCarreraController::class, 'index'])->name('academico-plan_estudio-tipo_carrera-index');
    Route::post('/academico/plan_estudio/tipo_carrera/save', [TipoCarreraController::class, 'save'])->name('academico-plan_estudio-tipo_carrera-save');
    Route::delete('/academico/plan_estudio/tipo_carrera/{id}/delete', [TipoCarreraController::class, 'delete'])->name('academico-plan_estudio-tipo_carrera-delete');

    Route::get('/academico/plan_estudio/carrera', [CarreraController::class, 'index'])->name('academico-plan_estudio-carrera-index');
    Route::post('/academico/plan_estudio/carrera/save', [CarreraController::class, 'save'])->name('academico-plan_estudio-carrera-save');
    Route::delete('/academico/plan_estudio/carrera/{id}/delete', [CarreraController::class, 'delete'])->name('academico-plan_estudio-carrera-delete');

    Route::get('/academico/plan_estudio/area', [AreaController::class, 'index'])->name('academico-plan_estudio-area-index');
    Route::post('/academico/plan_estudio/area/save', [AreaController::class, 'save'])->name('academico-plan_estudio-area-save');
    Route::delete('/academico/plan_estudio/area/{id}/delete', [AreaController::class, 'delete'])->name('academico-plan_estudio-area-delete');

    Route::get('/academico/plan_estudio/tipo-tipo-requisito', [TipoRequisitoController::class, 'index'])->name('academico-plan_estudio-tipo_requisito-index');
    Route::post('/academico/plan_estudio/tipo-tipo-requisito/save', [TipoRequisitoController::class, 'save'])->name('academico-plan_estudio-tipo_requisito-save');
    Route::delete('/academico/plan_estudio/tipo-tipo-requisito/{id}/delete', [TipoRequisitoController::class, 'delete'])->name('academico-plan_estudio-tipo_requisito-delete');

    Route::get('/academico/plan_estudio/tipo-unidad-academica', [TipoUnidadAcademicaController::class, 'index'])->name('academico-plan_estudio-tipo_unidad_academica-index');
    Route::post('/academico/plan_estudio/tipo-unidad-academico/save', [TipoUnidadAcademicaController::class, 'save'])->name('academico-plan_estudio-tipo_unidad_academica-save');
    Route::delete('/academico/plan_estudio/tipo-unidad-academico/{id}/delete', [TipoUnidadAcademicaController::class, 'delete'])->name('academico-plan_estudio-tipo_unidad_academica-delete');

    Route::get('/academico/plan_estudio/unidad-academica', [UnidadAcademicaController::class, 'index'])->name('academico-plan_estudio-unidad_academica-index');
    Route::post('/academico/plan_estudio/unidad-academico/save', [UnidadAcademicaController::class, 'save'])->name('academico-plan_estudio-unidad_academica-save');
    Route::delete('/academico/plan_estudio/unidad-academico/{id}/delete', [UnidadAcademicaController::class, 'delete'])->name('academico-plan_estudio-unidad_academica-delete');

    Route::get('/academico/plan_estudio/malla-curricular', [MallaCurricularController::class, 'index'])->name('academico-plan_estudio-malla_curricular-index');
    Route::post('/academico/plan_estudio/malla-curricular/save', [MallaCurricularController::class, 'save'])->name('academico-plan_estudio-malla_curricular-save');
    Route::delete('/academico/plan_estudio/malla-curricular/{id}/delete', [MallaCurricularController::class, 'delete'])->name('academico-plan_estudio-malla_curricular-delete');

    Route::get('/academico/semestre', [SemestreController::class, 'index'])->name('academico-semestre-index');
    Route::post('/academico/semestre/save', [SemestreController::class, 'save'])->name('academico-semestre-save');
    Route::delete('/academico/semestre/{id}/delete', [SemestreController::class, 'delete'])->name('academico-semestre-delete');
});
