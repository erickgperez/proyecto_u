<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Academico\Sede;
use App\Models\Ingreso\Convocatoria;
use App\Models\PlanEstudio\Carrera;
use App\Services\SolicitudService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IngresoReportesController extends Controller
{

    public function __construct(private SolicitudService $solicitudService) {}

    public function aspirantes(Request $request): Response
    {

        return Inertia::render('ingreso/reportes/ReporteSeleccion');
    }

    public function getDataAspirantes(Request $request)
    {
        $convocatoriaId = $request->get('convocatoria');
        $estadoSeleccionIds = $request->get('estadoSeleccion');
        $sedeSeleccionIds = $request->get('sedeSeleccion');
        $carreraSeleccionIds = $request->get('carreraSeleccion');



        $querySol = $this->solicitudService->getQB()
            ->orderBy('sede.nombre', 'ASC')
            ->where('solicitud.modelo_id', $convocatoriaId)
            ->where('solicitud.modelo_type', Convocatoria::class);


        if (count($estadoSeleccionIds) == 1) {
            // 1 = seleccionado, 0 = no seleccionado
            $querySol->where('convocatoria_aspirante.seleccionado', $estadoSeleccionIds[0]);
        }

        if (!empty($sedeSeleccionIds)) {
            $querySol->whereIn('solicitud.sede_id', $sedeSeleccionIds);
        }

        if (!empty($carreraSeleccionIds)) {
            $querySol->whereIn('carrera.id', $carreraSeleccionIds);
        }

        $aspirantes = $querySol->get();

        $sedes = Sede::whereIn('id', $sedeSeleccionIds)->get();
        $carreras = Carrera::whereIn('id', $carreraSeleccionIds)->get();

        return response()->json([
            'sedes' => $sedes,
            'carreras' => $carreras,
            'aspirantes' => $aspirantes,
        ]);
    }
}
