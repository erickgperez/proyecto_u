<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Academica\CarreraSede;
use App\Models\Academica\Sede;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\Convocatoria;
use App\Models\Ingreso\ConvocatoriaAspirante;
use App\Models\Municipio;
use App\Models\Persona;
use App\Models\PlanEstudio\Carrera;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Flujo;
use App\Models\Workflow\Solicitud;
use App\Models\Workflow\SolicitudCarreraSede;
use App\Models\Workflow\TipoCarreraSedeSolicitud;
use App\Models\Workflow\TipoFlujo;
use App\Services\SolicitudService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AspiranteController extends Controller
{

    protected $solicitudService;

    public function __construct(SolicitudService $solicitudService)
    {
        $this->solicitudService = $solicitudService;
    }
    public function save(Request $request) {}

    public function delete(int $id) {}

    public function datosPersonalesRestringido()
    {
        $user = Auth::user();
        $persona = $user->personas()->first();

        return response()->json(['status' => 'ok', 'message' => '', 'persona' => $persona]);
    }

    public function seleccion(Request $request): Response
    {

        $convocatorias = Convocatoria::with([
            'carrerasSedes' => [
                'carrera',
                'sede',
            ],
            'configuracion'
        ])->orderBy('nombre', 'asc')
            ->get();


        return Inertia::render('ingreso/Seleccion', ['convocatorias' => $convocatorias]);
    }

    public function aplicarSeleccion(int $id, $seleccionado = true, $idSolicitudCarreraSede = null)
    {
        $solicitud = Solicitud::find($id);
        $aspirante = $solicitud->solicitante;
        $convocatoria = $solicitud->modelo;

        $convocatoriaAspirante = ConvocatoriaAspirante::whereBelongsTo($aspirante)
            ->whereBelongsTo($convocatoria)
            ->first();
        $convocatoriaAspirante->seleccionado = $seleccionado;
        $convocatoriaAspirante->solicitud_carrera_sede_id = $idSolicitudCarreraSede;

        $convocatoriaAspirante->save();

        return response()->json(['status' => 'ok', 'message' => '']);
    }

    public function aceptarSeleccion(int $id)
    {
        $solicitud = Solicitud::with([
            'solicitante',
            'etapa',
            'estado',
            'modelo'
        ])->where('id', $id)->first();


        $convocatoriaAspirante = ConvocatoriaAspirante::with('solicitudCarreraSede')
            ->whereBelongsTo($solicitud->solicitante)
            ->whereBelongsTo($solicitud->modelo)
            ->first();

        dd($convocatoriaAspirante->solicitudCarreraSede->carreraSede->carrera);

        $solicitud->pasarSiguienteEtapa();
        $solicitud->save();

        //Guardar en el historial de solicitud
        $solicitud->guardarHistorial();

        return response()->json(['status' => 'ok', 'message' => '']);
    }
}
