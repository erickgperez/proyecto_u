<?php

use App\Http\Controllers\Administracion\EstadoController;
use App\Http\Controllers\Administracion\EtapaController;
use App\Http\Controllers\Administracion\FlujoController;
use App\Http\Controllers\Administracion\PermisosController;
use App\Http\Controllers\Administracion\PersonaController;
use App\Http\Controllers\Administracion\RolesController;
use App\Http\Controllers\Administracion\SimulacionController;
use App\Http\Controllers\Administracion\TipoFlujoController;
use App\Http\Controllers\Administracion\TransicionController;
use App\Http\Controllers\Administracion\UsuarioController;
use App\Models\Workflow\Etapa;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/administracion/persona', [PersonaController::class, 'index'])->name('administracion-persona-index');
    Route::post('/administracion/persona/save', [PersonaController::class, 'save'])->name('administracion-persona-save');
    Route::post('/administracion/persona/{id}/datos-contacto/save', [PersonaController::class, 'datosContactoSave'])->name('administracion-persona-datos-contacto-save');
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

    Route::get('/proceso/tipo', [TipoFlujoController::class, 'index'])->name('proceso-tipo-index');
    Route::post('/proceso/tipo/save', [TipoFlujoController::class, 'save'])->name('proceso-tipo-save');
    Route::delete('/proceso/tipo/{id}/delete', [TipoFlujoController::class, 'delete'])->name('proceso-tipo-delete');

    Route::get('/procesos/proceso', [FlujoController::class, 'index'])->name('procesos-proceso-index');
    Route::post('/procesos/proceso/save', [FlujoController::class, 'save'])->name('procesos-proceso-save');
    Route::delete('/procesos/proceso/{id}/delete', [FlujoController::class, 'delete'])->name('procesos-proceso-delete');

    Route::get('/proceso/etapa', [EtapaController::class, 'index'])->name('proceso-etapa-index');
    Route::post('/proceso/etapa/save', [EtapaController::class, 'save'])->name('proceso-etapa-save');
    Route::delete('/proceso/eteapa/{id}/delete', [EtapaController::class, 'delete'])->name('proceso-etapa-delete');

    Route::get('/proceso/transicion', [TransicionController::class, 'index'])->name('proceso-transicion-index');
    Route::post('/proceso/transicion/save', [TransicionController::class, 'save'])->name('proceso-transicion-save');
    Route::delete('/proceso/transicion/{id}/delete', [TransicionController::class, 'delete'])->name('proceso-transicion-delete');

    Route::get('/administracion/simulacion/{cantidad}', [SimulacionController::class, 'index'])->name('administracion-simulacion-index');
});
