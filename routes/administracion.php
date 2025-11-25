<?php

use App\Http\Controllers\Administracion\DocenteController;
use App\Http\Controllers\Administracion\EstadoController;
use App\Http\Controllers\Administracion\EtapaController;
use App\Http\Controllers\Administracion\FlujoController;
use App\Http\Controllers\Administracion\PerfilController;
use App\Http\Controllers\Administracion\PermisosController;
use App\Http\Controllers\Administracion\RolesController;
use App\Http\Controllers\Administracion\SimulacionController;
use App\Http\Controllers\Administracion\TipoCalendarizacionController;
use App\Http\Controllers\Administracion\TipoEventoController;
use App\Http\Controllers\Administracion\TipoFlujoController;
use App\Http\Controllers\Administracion\TransicionController;
use App\Http\Controllers\Administracion\UsuarioController;
use App\Http\Controllers\Documento\DocumentoController;
use App\Http\Controllers\Ingreso\AspiranteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {

    Route::get('/administracion/perfil/aspirante', [PerfilController::class, 'indexAspirante'])->name('administracion-perfil-aspirante-index');
    Route::get('/administracion/perfil/docente', [PerfilController::class, 'indexDocente'])->name('administracion-perfil-docente-index');
    Route::post('/administracion/perfil/save', [PerfilController::class, 'save'])->name('administracion-perfil-save');
    Route::post('/administracion/perfil/{id}/datos-contacto/save', [PerfilController::class, 'datosContactoSave'])->name('administracion-perfil-datos-contacto-save');
    Route::get('/administracion/perfil/{id}/info', [PerfilController::class, 'personaInfo'])->name('administracion-perfil-info');
    Route::delete('/administracion/perfil/{id}/delete', [PerfilController::class, 'delete'])->name('administracion-perfil-delete');

    Route::get('/administracion/perfil/{id}/docente/data', [DocenteController::class, 'data'])->name('administracion-perfil-docente-data');
    Route::post('/administracion/perfil/{id}/docente/asignacion/save', [DocenteController::class, 'asignacionSave'])->name('administracion-perfil-docente-asignacion-save');

    Route::get('/administracion/perfil/{id}/documentos', [DocumentoController::class, 'documentosPersona'])->name('administracion-perfil-documentos');
    Route::post('/administracion/documento/save', [DocumentoController::class, 'documentoSave'])->name('administracion-documento-save');
    Route::get('/administracion/documento/{id}/descargar', [DocumentoController::class, 'documentoDescargar'])->name('administracion-documento-descargar');

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

    Route::get('/calendarizacion/tipo', [TipoCalendarizacionController::class, 'index'])->name('calendarizacion-tipo-index');
    Route::post('/calendarizacion/tipo/save', [TipoCalendarizacionController::class, 'save'])->name('calendarizacion-tipo-save');
    Route::delete('/calendarizacion/tipo/{id}/delete', [TipoCalendarizacionController::class, 'delete'])->name('calendarizacion-tipo-delete');

    Route::get('/calendarizacion/tipo-evento', [TipoEventoController::class, 'index'])->name('calendarizacion-tipo_evento-index');
    Route::post('/calendarizacion/tipo-evento/save', [TipoEventoController::class, 'save'])->name('calendarizacion-tipo_evento-save');
    Route::delete('/calendarizacion/tipo-evento/{id}/delete', [TipoEventoController::class, 'delete'])->name('calendarizacion-tipo_evento-delete');
});
