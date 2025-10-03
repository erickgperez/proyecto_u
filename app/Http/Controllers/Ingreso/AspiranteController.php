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
use App\Models\Workflow\Historial;
use App\Models\Workflow\Solicitud;
use App\Models\Workflow\TipoFlujo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $aspirante = $persona->aspirantes()->first();

        //Buscar la solicitud más reciente con el rol de aspirante
        $solicitud = $persona->solicitudes()->with('estado', 'etapa', 'persona')
            ->where('rol_id', $rolAspirante->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $flujo = $solicitud->flujo;
        $etapasOrden = $flujo->etapasEnOrden();

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
        $historial = new Historial;
        $historial->solicitud()->associate($solicitud);
        $historial->estado()->associate($estado);
        $historial->etapa()->associate($etapa);
        $historial->save();

        $solicitudData = Solicitud::with('estado', 'etapa', 'persona')->find($solicitud->id);
        $etapasOrden = $flujo->etapasEnOrden();

        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }

    public function convocatoriaCarrera(int $id)
    {
        $aspirante = Aspirante::find($id);

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
        $aspirante = Aspirante::find($id);

        $convocatoria = Convocatoria::find($request->get('convocatoria_id'));
        $rolAspirante = Rol::where('name', 'aspirante')->first();
        $tipo_flujo = TipoFlujo::where('codigo', 'INGRESO')->first();
        $flujo = Flujo::where('tipo_flujo_id', $tipo_flujo->id)->first();

        $persona = $aspirante->persona;

        $solicitud = Solicitud::where([
            'rol_id' => $rolAspirante->id,
            'flujo_id' => $flujo->id,
            'persona_id' => $persona->id
        ])->first();

        //Guardar convocatoria_aspirante
        //Guardar solicitud_carrera_sede
        //Pasar la solicitud a la siguiente etapa
        //Guardar en el historial de solicitud
        //Regresar la solicitud

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'solicitud' => $solicitud]);
    }
}
