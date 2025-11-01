<?php

use App\Http\Controllers\Workflow\SolicitudIngresoController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth', 'verified')->group(function () {
    // Ingreso Solicitud
    Route::get('/workflow/ingreso/{idPersona}/solicitud', [SolicitudIngresoController::class, 'solicitud'])->name('workflow-ingreso-solicitud');
    Route::get('/workflow/ingreso/aspirante/{id}/solicitud/crear', [SolicitudIngresoController::class, 'solicitudCrear'])->name('workflow-ingreso-aspirante-solicitud-crear');

    // Ingreso SelecionProcesoCarrera
    Route::get('/workflow/ingreso/aspirante/{id}/convocatoria', [SolicitudIngresoController::class, 'convocatoriaCarrera'])->name('workflow-ingreso-aspirante-convocatoria-carrera');
    Route::post('/workflow/ingreso/solicitud/{id}/seleccion-carrera', [SolicitudIngresoController::class, 'seleccionCarrera'])->name('workflow-ingreso-solicitud-seleccion-carrera');
});
