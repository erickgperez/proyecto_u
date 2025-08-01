<?php

use App\Http\Controllers\Ingreso\CandidatosController;
use App\Http\Controllers\Ingreso\UploadFileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/ingreso/bachillerato/cargar-archivo', [UploadFileController::class, 'index'])->name('ingreso-bachillerato-cargar-archivo');
    Route::post('/ingreso/bachillerato/import-data', [UploadFileController::class, 'import'])->name('ingreso-import-data-bachillerato');
    Route::get('/ingreso/bachillerato/candidatos', [CandidatosController::class, 'index'])->name('ingreso-bachillerato-candidatos');
    Route::post('/ingreso/bachillerato/candidatos/listado', [CandidatosController::class, 'listado'])->name('ingreso-bachillerato-candidatos-listado');
    Route::patch('/ingreso/bachillerato/candidato/invitacion', [CandidatosController::class, 'invitacion'])->name('ingreso-bachillerato-candidato-invitacion');
});
