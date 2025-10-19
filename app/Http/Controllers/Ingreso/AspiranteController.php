<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Academica\CarreraSede;
use App\Models\Academica\Sede;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Ingreso\Aspirante;
use App\Models\Ingreso\Convocatoria;
use App\Models\Municipio;
use App\Models\Persona;
use App\Models\PlanEstudio\Carrera;
use App\Models\Rol;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Flujo;
use App\Models\Workflow\Solicitud;
use App\Models\Workflow\SolicitudCarreraSede;
use App\Models\Workflow\TipoCarreraSedeSolicitud;
use App\Models\Workflow\TipoFlujo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AspiranteController extends Controller
{

    public function save(Request $request) {}

    public function delete(int $id) {}

    public function datosPersonalesRestringido()
    {
        $user = Auth::user();
        $persona = $user->personas()->first();

        return response()->json(['status' => 'ok', 'message' => '', 'persona' => $persona]);
    }

    public function solicitud(int $idPersona)
    {
        $rolAspirante = Rol::where('name', 'aspirante')->first();

        $persona = Persona::find($idPersona);
        $aspirante = $persona->aspirantes()->orderBy('created_at', 'desc')->first();

        //Buscar la solicitud más reciente con el rol de aspirante
        $solicitud = $persona->solicitudes()->with('estado', 'etapa', 'persona')
            ->where('rol_id', $rolAspirante->id)
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

    public function solicitudCrear(int $idPersona)
    {
        $rolAspirante = Rol::where('name', 'aspirante')->first();
        $tipo_flujo = TipoFlujo::where('codigo', 'INGRESO')->first();
        $flujo = Flujo::where('tipo_flujo_id', $tipo_flujo->id)->first();
        $estado = Estado::where('codigo', 'INICIO')->first();
        $etapa = $flujo->primeraEtapa();
        $persona = Persona::find($idPersona);
        $aspirante = $persona->aspirantes()->first();

        // Crear la solicitud
        $solicitud = new Solicitud;
        $solicitud->rol()->associate($rolAspirante);
        $solicitud->flujo()->associate($flujo);
        $solicitud->estado()->associate($estado);
        $solicitud->etapa()->associate($etapa);
        $solicitud->persona()->associate($persona);
        $solicitud->save();

        //Guardarla en el historial de la solicitud
        $solicitud->guardarHistorial();

        $solicitudData = Solicitud::with('estado', 'etapa', 'persona')->find($solicitud->id);
        $etapasOrden = $flujo->etapasEnOrden();

        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }

    public function convocatoriaCarrera(int $id)
    {

        $convocatorias = Convocatoria::all();
        $sedes = Sede::orderBy('nombre')->get();
        $distritos = Distrito::orderBy('descripcion')->get();
        $departamentos = Departamento::orderBy('descripcion')->get();
        $municipios = Municipio::orderBy('descripcion')->get();
        $carreras = Carrera::orderBy('nombre')->get();
        $carrerasSedes = CarreraSede::with('carrera', 'sede')->get();

        return response()->json([
            'status' => 'ok',
            'message' => '',
            'convocatorias' => $convocatorias,
            'distritos' => $distritos,
            'departamentos' => $departamentos,
            'municipios' => $municipios,
            'sedes' => $sedes,
            'carreras' => $carreras,
            'carrerasSedes' => $carrerasSedes
        ]);
    }

    public function seleccionCarrera(int $id, Request $request)
    {
        $solicitud = Solicitud::find($id);

        $convocatoria = Convocatoria::find($request->get('convocatoria_id'));
        $solicitud->modelo()->associate($convocatoria);
        $persona = $solicitud->persona;
        $aspirante = $persona->aspirantes()->orderBy('created_at', 'desc')->first();

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

    public function seleccion(Request $request): Response
    {

        $convocatorias = Convocatoria::with([
            'carrerasSedes' => [
                'carrera',
                'sede'
            ],
        ])->orderBy('nombre', 'asc')
            ->get();


        return Inertia::render('ingreso/Seleccion', ['convocatorias' => $convocatorias]);
    }
}
