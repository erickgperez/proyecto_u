<?php

use App\Http\Controllers\Administracion\PersonaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/administracion/persona', [PersonaController::class, 'index'])->name('administracion-persona-index');
    Route::post('/administracion/persona/save', [PersonaController::class, 'save'])->name('administracion-persona-save');
    Route::delete('/administracion/persona/{id}/delete', [PersonaController::class, 'delete'])->name('administracion-persona-delete');
});
