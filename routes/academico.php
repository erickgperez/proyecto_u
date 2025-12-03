<?php

use App\Http\Controllers\Academico\AreaController;
use App\Http\Controllers\Academico\CarreraController;
use App\Http\Controllers\Academico\EstadoController;
use App\Http\Controllers\Academico\SedeController;
use App\Http\Controllers\Academico\GradoController;
use App\Http\Controllers\Academico\MallaCurricularController;
use App\Http\Controllers\Academico\SemestreController;
use App\Http\Controllers\Academico\OfertaController;
use App\Http\Controllers\Academico\TipoCarreraController;
use App\Http\Controllers\Academico\TipoCursoController;
use App\Http\Controllers\Academico\TipoRequisitoController;
use App\Http\Controllers\Academico\TipoUnidadAcademicaController;
use App\Http\Controllers\Academico\UnidadAcademicaController;
use App\Http\Controllers\Academico\UsoEstadoController;
use App\Http\Controllers\Administracion\DocenteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Academico\EstudianteController;
use App\Http\Controllers\Academico\EvaluacionController;
use App\Http\Controllers\Academico\ExpedienteController;
use App\Http\Controllers\Academico\FormaImparteController;

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
    Route::get('/academico/plan_estudio/carrera/sedes', [CarreraController::class, 'getCarreraSede'])->name('academico-plan_estudio-carrera-sede');

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
    Route::get('/academico/semestre/activos', [SemestreController::class, 'activos'])->name('academico-semestre-activos');
    Route::post('/academico/semestre/save', [SemestreController::class, 'save'])->name('academico-semestre-save');
    Route::delete('/academico/semestre/{id}/delete', [SemestreController::class, 'delete'])->name('academico-semestre-delete');

    Route::get('/academico/semestre/{id}/oferta', [OfertaController::class, 'index'])->name('academico-semestre-oferta-index');
    Route::post('/academico/semestre/{id}/ofertar/{idCarreraUnidadAcademica}', [OfertaController::class, 'ofertar'])->name('academico-semestre-ofertar');
    Route::get('/academico/semestre/{id}/oferta/{idCarreraUnidadAcademica}/detalle', [OfertaController::class, 'getOfertaDetalle'])->name('academico-semestre-oferta-detalle');
    Route::post('/academico/semestre/oferta/detalle/save', [OfertaController::class, 'ofertaDetalleSave'])->name('academico-semestre-oferta-detalle-save');
    Route::get('/academico/semestre/{id}/oferta/{idCarreraUnidadAcademica}/titular/{idDocente}/save', [OfertaController::class, 'saveDocenteTitular'])->name('academico-semestre-oferta-docente-titular-save');


    Route::get('/academico/tipo-curso', [TipoCursoController::class, 'index'])->name('academico-tipo_curso-index');
    Route::post('/academico/tipo-curso/save', [TipoCursoController::class, 'save'])->name('academico-tipo_curso-save');
    Route::delete('/academico/tipo-curso/{uuid}/delete', [TipoCursoController::class, 'delete'])->name('academico-tipo_curso-delete');

    Route::get('/academico/uso-estado', [UsoEstadoController::class, 'index'])->name('academico-uso_estado-index');
    Route::post('/academico/uso-estado/save', [UsoEstadoController::class, 'save'])->name('academico-uso_estado-save');
    Route::delete('/academico/uso-estado/{uuid}/delete', [UsoEstadoController::class, 'delete'])->name('academico-uso_estado-delete');

    Route::get('/academico/estado', [EstadoController::class, 'index'])->name('academico-estado-index');
    Route::post('/academico/estado/save', [EstadoController::class, 'save'])->name('academico-estado-save');
    Route::delete('/academico/estado/{uuid}/delete', [EstadoController::class, 'delete'])->name('academico-estado-delete');

    Route::get('/academico/forma-imparte', [FormaImparteController::class, 'index'])->name('academico-forma_imparte-index');
    Route::post('/academico/forma-imparte/save', [FormaImparteController::class, 'save'])->name('academico-forma_imparte-save');
    Route::delete('/academico/forma-imparte/{uuid}/delete', [FormaImparteController::class, 'delete'])->name('academico-forma_imparte-delete');

    Route::get('/academico/semestre/{uuidSemestre}/docente/{uuidDocente}/carga', [DocenteController::class, 'carga'])->name('academico-semestre-docente-carga');
    Route::post('/academico/semestre/docente/{uuid}/carga/save', [DocenteController::class, 'cargaSave'])->name('academico-semestre-docente-carga-save');

    Route::get('/academico/persona/{uuid}/estudiante/data', [EstudianteController::class, 'getEstudianteData'])->name('academico-persona-estudiante-data');
    Route::get('/academico/estudiante/{uuid}/inscripcion/carrera-sede/{id}', [EstudianteController::class, 'inscripcion'])->name('academico-estudiante-inscripcion-carrera-sede');
    Route::post('/academico/estudiante/{uuid}/inscripcion/semestre/{uuidSemestre}/carrera-sede/{id}/save', [ExpedienteController::class, 'inscripcionSave'])->name('academico-estudiante-inscripcion-save');
    Route::get('/academico/estudiante/{uuid}/expediente/carrera-sede/{id}', [ExpedienteController::class, 'expediente'])->name('academico-estudiante-expediente-carrera-sede');
    Route::get('/academico/estudiante/{uuid}/perfil', [EstudianteController::class, 'perfil'])->name('academico-estudiante-perfil');

    Route::get('/academico/persona/{uuid}/docente/data', [DocenteController::class, 'getDocenteData'])->name('academico-persona-docente-data');


    Route::get('/academico/oferta/{uuid}/evaluacion', [EvaluacionController::class, 'index'])->name('academico-evaluacion-index');
    Route::post('/academico/oferta/{uuid}/evaluacion/save', [EvaluacionController::class, 'save'])->name('academico-evaluacion-save');
    Route::delete('/academico/evaluacion/{uuid}/delete', [EvaluacionController::class, 'delete'])->name('academico-evaluacion-delete');
});
