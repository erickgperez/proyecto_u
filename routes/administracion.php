<?php

use App\Http\Controllers\Administracion\EstadoController;
use App\Http\Controllers\Administracion\PermisosController;
use App\Http\Controllers\Administracion\PersonaController;
use App\Http\Controllers\Administracion\ProcesoController;
use App\Http\Controllers\Administracion\RolesController;
use App\Http\Controllers\Administracion\UsuarioController;
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

    Route::get('/seguridad/usuario', [UsuarioController::class, 'index'])->name('seguridad-usuario-index');
    Route::post('/seguridad/usuario/save', [UsuarioController::class, 'save'])->name('seguridad-usuario-save');
    Route::delete('/seguridad/usuario/{id}/delete', [UsuarioController::class, 'delete'])->name('seguridad-usuario-delete');

    Route::get('/proceso/estado', [EstadoController::class, 'index'])->name('proceso-estado-index');
    Route::post('/proceso/estado/save', [EstadoController::class, 'save'])->name('proceso-estado-save');
    Route::delete('/proceso/estado/{id}/delete', [EstadoController::class, 'delete'])->name('proceso-estado-delete');
});
