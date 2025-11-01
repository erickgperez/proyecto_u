<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Academica\CarreraSede;
use App\Models\Academica\Sede;
use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\Convocatoria;
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
        $aspirante = $persona->aspirantes()->orderBy('created_at', 'desc')->first();

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

        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitud, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }

    public function solicitudCrear(int $id)
    {
        $tipo_flujo = TipoFlujo::where('codigo', 'INGRESO')->first();
        $flujo = Flujo::where('tipo_flujo_id', $tipo_flujo->id)->first();
        $estado = Estado::where('codigo', 'INICIO')->first();
        $etapa = $flujo->primeraEtapa();
        $aspirante = Aspirante::find($id);

        // Crear la solicitud
        $solicitud = new Solicitud;
        $solicitud->flujo()->associate($flujo);
        $solicitud->estado()->associate($estado);
        $solicitud->etapa()->associate($etapa);
        $solicitud->solicitante()->associate($aspirante);
        $solicitud->save();

        //Guardarla en el historial de la solicitud
        $solicitud->guardarHistorial();

        $solicitudData = Solicitud::with('estado', 'etapa', 'modelo', 'solicitante')->find($solicitud->id);
        $etapasOrden = $flujo->etapasEnOrden();

        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }

    public function convocatoriaCarrera(int $id)
    {

        $aspirante = Aspirante::find($id);
        $convocatorias = Convocatoria::all();
        $convocatoria = $aspirante->convocatorias()->orderBy('created_at', 'desc')->first();

        $carreras = Carrera::orderBy('nombre')->get();
        /*$sedes = Sede::orderBy('nombre')->get();
        $distritos = Distrito::orderBy('descripcion')->get();
        $departamentos = Departamento::orderBy('descripcion')->get();
        $municipios = Municipio::orderBy('descripcion')->get();
        $carrerasSedes = CarreraSede::with('carrera', 'sede')->get();*/

        return response()->json([
            'status' => 'ok',
            'message' => '',
            'convocatorias' => $convocatorias,
            'convocatoria' => $convocatoria,
            'carreras' => $carreras,
            /*'distritos' => $distritos,
            'departamentos' => $departamentos,
            'municipios' => $municipios,
            'sedes' => $sedes,
            'carrerasSedes' => $carrerasSedes*/
        ]);
    }

    public function seleccionCarrera(int $id, Request $request)
    {
        $solicitud = Solicitud::find($id);

        $convocatoria = Convocatoria::find($request->get('convocatoria_id'));
        $sede = Sede::find($request->get('sede_id'));
        $solicitud->modelo()->associate($convocatoria);
        $solicitud->sede()->associate($sede);
        $aspirante = $solicitud->solicitante;

        $flujo = $solicitud->flujo;

        //Guardar convocatoria_aspirante
        //Verificar si ya existe
        $convocatoriaAspirante = $aspirante->convocatorias()->where('convocatoria_id', $convocatoria->id)->first();

        if (!$convocatoriaAspirante) {
            //No existe, agregarla
            $aspirante->convocatorias()->syncWithoutDetaching([$convocatoria->id]);
        }
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
        $etapasOrden = $flujo->etapasEnOrden();
        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitud, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
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
