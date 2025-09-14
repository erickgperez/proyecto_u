<?php

use App\Http\Controllers\Academica\SedeController;
use App\Http\Controllers\Academica\GradoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/academica/sede', [SedeController::class, 'index'])->name('academica-sede-index');
    Route::post('/academica/sede/save', [SedeController::class, 'save'])->name('academica-sede-save');
    Route::delete('/academica/sede/{id}/delete', [SedeController::class, 'delete'])->name('academica-sede-delete');

    Route::get('/plan_estudio/grado', [GradoController::class, 'index'])->name('plan-estudio-grado-index');
    Route::post('/plan_estudio/grado/save', [GradoController::class, 'save'])->name('plan-estudio-grado-save');
    Route::delete('/plan_estudio/grado/{id}/delete', [GradoController::class, 'delete'])->name('plan-estudio-grado-delete');
});
