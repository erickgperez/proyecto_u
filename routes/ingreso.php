<?php

use App\Http\Controllers\Ingreso\AspiranteController;
use App\Http\Controllers\Ingreso\CandidatosController;
use App\Http\Controllers\Ingreso\ConvocatoriaController;
use App\Http\Controllers\Ingreso\UploadFileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/ingreso/bachillerato/cargar-archivo', [UploadFileController::class, 'index'])->name('ingreso-bachillerato-cargar-archivo');
    Route::post('/ingreso/bachillerato/import-data', [UploadFileController::class, 'import'])->name('ingreso-import-data-bachillerato');
    Route::get('/ingreso/bachillerato/candidatos', [CandidatosController::class, 'index'])->name('ingreso-bachillerato-candidatos');
    Route::post('/ingreso/bachillerato/candidatos/listado', [CandidatosController::class, 'listado'])->name('ingreso-bachillerato-candidatos-listado');
    Route::get('/ingreso/bachillerato/candidatos/resumen', [CandidatosController::class, 'resumen'])->name('ingreso-bachillerato-candidatos-resumen');
    Route::post('/ingreso/bachillerato/candidatos/invitaciones', [CandidatosController::class, 'invitaciones'])->name('ingreso-bachillerato-candidatos-invitaciones');
    Route::patch('/ingreso/bachillerato/candidato/save/field', [CandidatosController::class, 'saveField'])->name('ingreso-bachillerato-candidato-save-field');

    Route::get('/ingreso/convocatoria', [ConvocatoriaController::class, 'index'])->name('ingreso-convocatoria-index');
    Route::post('/ingreso/convocatoria/save', [ConvocatoriaController::class, 'save'])->name('ingreso-convocatoria-save');
    Route::delete('/ingreso/convocatoria/{id}/delete', [ConvocatoriaController::class, 'delete'])->name('ingreso-convocatoria-delete');
    Route::get('/ingreso/convocatoria/{id}/invitaciones-pendientes', [ConvocatoriaController::class, 'enviarInvitacionesPendientes'])->name('ingreso-convocatoria-invitaciones-pendientes');
    Route::get('/ingreso/convocatoria/{id}/oferta', [ConvocatoriaController::class, 'ofertaCarreras'])->name('ingreso-convocatoria-oferta');


    Route::get('/ingreso/afiche/download/{id}', [ConvocatoriaController::class, 'aficheDownload'])->name('ingreso-convocatoria-afiche-download');
    Route::delete('/ingreso/afiche/{id}/delete', [ConvocatoriaController::class, 'aficheDelete'])->name('ingreso-convocatoria-afiche-delete');

    Route::get('/ingreso/aspirante/{idPersona}/solicitud', [AspiranteController::class, 'solicitud'])->name('ingreso-aspirante-solicitud');
    Route::get('/ingreso/aspirante/{idPersona}/solicitud/crear', [AspiranteController::class, 'solicitudCrear'])->name('ingreso-aspirante-solicitud-crear');
    Route::get('/ingreso/aspirante/{id}/convocatoria', [AspiranteController::class, 'convocatoriaCarrera'])->name('ingreso-aspirante-convocatoria-carrera');
    Route::post('/ingreso/aspirante/{id}/seleccion-carrera', [AspiranteController::class, 'seleccionCarrera'])->name('ingreso-aspirante-seleccion-carrera');
});
