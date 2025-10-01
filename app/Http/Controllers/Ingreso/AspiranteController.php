<?php

namespace App\Http\Controllers\Ingreso;

use App\Http\Controllers\Controller;
use App\Models\Ingreso\Aspirante;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Flujo;
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

        $solicitudData = Solicitud::with('estado', 'etapa', 'persona')->find($solicitud->id);
        $etapasOrden = $flujo->etapasEnOrden();

        return response()->json(['status' => 'ok', 'message' => '', 'solicitud' => $solicitudData, 'aspirante' => $aspirante, 'etapas' => $etapasOrden]);
    }
}
