<?php

use App\Http\Controllers\EventoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/general/calendario/{id}/actividad', [EventoController::class, 'index'])->name('general-actividad-index');
    Route::post('/general/calendario/{id}/actividad/save', [EventoController::class, 'save'])->name('general-actividad-save');
    Route::delete('/general/actividad/{id}/delete', [EventoController::class, 'delete'])->name('general-actividad-delete');
});
