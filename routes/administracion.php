<?php

use App\Http\Controllers\Administracion\PermisosController;
use App\Http\Controllers\Administracion\PersonaController;
use App\Http\Controllers\Administracion\RolesController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/administracion/persona', [PersonaController::class, 'index'])->name('administracion-persona-index');
    Route::post('/administracion/persona/save', [PersonaController::class, 'save'])->name('administracion-persona-save');
    Route::delete('/administracion/persona/{id}/delete', [PersonaController::class, 'delete'])->name('administracion-persona-delete');

    Route::get('/seguridad/permisos', [PermisosController::class, 'index'])->name('seguridad-permisos-index');
    Route::post('/seguridad/permisos/save', [PermisosController::class, 'save'])->name('seguridad-permisos-save');
    Route::delete('/seguridad/permisos/{id}/delete', [PermisosController::class, 'delete'])->name('seguridad-permisos-delete');

    Route::get('/seguridad/roles', [RolesController::class, 'index'])->name('seguridad-roles-index');
    Route::post('/seguridad/roles/save', [RolesController::class, 'save'])->name('seguridad-roles-save');
    Route::delete('/seguridad/roles/{id}/delete', [RolesController::class, 'delete'])->name('seguridad-roles-delete');
});
