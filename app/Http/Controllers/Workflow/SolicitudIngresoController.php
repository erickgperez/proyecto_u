<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Sede;
use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\Convocatoria;
use App\Models\Persona;
use App\Models\PlanEstudio\Carrera;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Solicitud;
use App\Models\Workflow\SolicitudCarreraSede;
use App\Models\Workflow\TipoCarreraSedeSolicitud;
use App\Services\SolicitudService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SolicitudIngresoController extends Controller
{

    protected $solicitudService;

    public function __construct(SolicitudService $solicitudService)
    {
        $this->solicitudService = $solicitudService;
    }


    public function solicitud(int $idPersona)
    {
        $persona = Persona::find($idPersona);
        $aspirante = $persona->aspirante;

        $now = Carbon::now();
        $convocatorias = Convocatoria::with('configuracion')
            ->join('ingreso.convocatoria_configuracion as conf', 'ingreso.convocatoria.id', '=', 'conf.convocatoria_id')
            ->whereRaw('? BETWEEN conf.fecha_inicio_recepcion_solicitudes AND conf.fecha_fin_recepcion_solicitudes', [$now])
            ->get();

        //Verificar si ya está asociado a una convocatoria
        $convocatoriaAspirante = $aspirante->convocatorias()->orderBy('created_at', 'DESC')->first();

        //Buscar la solicitud más reciente con el rol de aspirante
        $solicitud = $this->solicitudService->getQB()
            ->where('solicitud.solicitante_id', $aspirante->id)
            ->where('solicitud.solicitante_type', Aspirante::class)
            ->orderBy('created_at', 'desc')
            ->first();

        $flujo = null;
        $etapasOrden = [];
        if ($solicitud) {
            $flujo = $solicitud->flujo;
            $etapasOrden = $flujo->etapasEnOrden();
        }

        return response()->json([
            'status' => 'ok',
            'message' => '',
            'solicitud' => $solicitud,
            'convocatorias' => $convocatorias,
            'convocatoriaAspirante' => $convocatoriaAspirante,
            'aspirante' => $aspirante,
            'etapas' => $etapasOrden
        ]);
    }

    public function solicitudCrear(int $id, $idConvocatoria)
    {

        $aspirante = Aspirante::find($id);
        $convocatoria = Convocatoria::find($idConvocatoria);
        $flujo = $convocatoria->flujo;
        $estado = Estado::where('codigo', 'INICIO')->first();
        $etapa = $flujo->primeraEtapa();

        // Crear la solicitud
        $solicitud = new Solicitud;
        $solicitud->flujo()->associate($flujo);
        $solicitud->estado()->associate($estado);
        $solicitud->etapa()->associate($etapa);
        $solicitud->solicitante()->associate($aspirante);
        $solicitud->modelo()->associate($convocatoria);
        $solicitud->save();

        //Guardarla en el historial de la solicitud
        $solicitud->guardarHistorial();

        //Guardar convocatoria_aspirante
        $aspirante->convocatorias()->syncWithoutDetaching([$convocatoria->id]);

        //Buscar la solicitud más reciente con el rol de aspirante
        $solicitudData = $this->solicitudService->getQB()
            ->where('solicitud.id', $solicitud->id)
            ->first();
        $etapasOrden = $flujo->etapasEnOrden();

        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }

    public function seleccionCarrera(int $id, Request $request)
    {
        $solicitud = Solicitud::find($id);

        $sede = Sede::find($request->get('sede_id'));
        $solicitud->sede()->associate($sede);
        $aspirante = $solicitud->solicitante;

        $flujo = $solicitud->flujo;
        //Guardar solicitud_carrera_sede
        //Borrar las que ya tenga asociadas
        $solicitud->solicitudCarrerasSede()->delete();
        //Ingresar las nuevas
        $carrerasSede = $request->get('carrera_sede');

        foreach ($carrerasSede as $i => $cs) {
            switch ($i) {
                case '1':
                    $tipoCarreraSedeSolicitud = TipoCarreraSedeSolicitud::where('codigo', 'SEGUNDA_OPCION')->first();
                    break;
                case '2':
                    $tipoCarreraSedeSolicitud = TipoCarreraSedeSolicitud::where('codigo', 'TERCERA_OPCION')->first();
                    break;
                default:
                    $tipoCarreraSedeSolicitud = TipoCarreraSedeSolicitud::where('codigo', 'PRIMERA_OPCION')->first();
                    break;
            }

            $carreraSede = CarreraSede::find($cs);
            $solicitudCarreraSede = new SolicitudCarreraSede();
            $solicitudCarreraSede->solicitud()->associate($solicitud);
            $solicitudCarreraSede->tipoCarreraSedeSolicitud()->associate($tipoCarreraSedeSolicitud);
            $solicitudCarreraSede->carreraSede()->associate($carreraSede);

            $solicitudCarreraSede->save();
        }

        //Pasar la solicitud a la siguiente etapa
        $solicitud->pasarSiguienteEtapa();
        $solicitud->save();

        //Guardar en el historial de solicitud
        $solicitud->guardarHistorial();

        //Regresar la solicitud
        $solicitudData = $this->solicitudService->getQB()
            ->where('solicitud.id', $solicitud->id)
            ->first();
        $etapasOrden = $flujo->etapasEnOrden();
        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }


    public function siguienteEtapa(int $id, Request $request)
    {
        $solicitud = Solicitud::find($id);

        //Pasar la solicitud a la siguiente etapa
        $solicitud->pasarSiguienteEtapa();
        $solicitud->save();

        $aspirante = $solicitud->solicitante;
        $flujo = $solicitud->flujo;

        //Guardar en el historial de solicitud
        $solicitud->guardarHistorial();

        $solicitudData = $this->solicitudService->getQB()
            ->where('solicitud.id', $solicitud->id)
            ->first();

        //Regresar la solicitud
        $etapasOrden = $flujo->etapasEnOrden();
        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }

    public function savePersona($id, Request $request)
    {
        $request->validate([
            'fecha_nacimiento' => 'required|date',
        ]);

        $persona = Persona::find($id);
        $persona->fecha_nacimiento = $request->get('fecha_nacimiento');
        $persona->save();

        return response()->json(['status' => 'ok', 'message' => '']);
    }
}
