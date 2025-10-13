<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Etapa;
use App\Models\Workflow\Flujo;
use App\Models\Workflow\Transicion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TransicionController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $Transiciones = Transicion::with('creator', 'updater', 'flujo', 'etapaOrigen', 'etapaDestino', 'estadoOrigen', 'estadoDestino')->orderBy('codigo')->get();
        $flujos = Flujo::orderBy('codigo')->where('activo', true)->get();
        $etapas = Etapa::orderBy('codigo')->get();
        $estados = Estado::orderBy('codigo')->get();

        return Inertia::render('administracion/Transicion', ['items' => $Transiciones, 'flujos' => $flujos, 'etapas' => $etapas, 'estados' => $estados]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'nombre' => 'required|string|max:255',
            'flujo_id' => ['required', 'integer', Rule::exists('pgsql.workflow.flujo', 'id')],
            'etapa_origen_id' => ['required', 'integer', Rule::exists('pgsql.workflow.etapa', 'id')],
            'etapa_destino_id' => ['required', 'integer', Rule::exists('pgsql.workflow.etapa', 'id')],
            'estado_origen_id' => ['required', 'integer', Rule::exists('pgsql.workflow.estado', 'id')],
            'estado_destino_id' => ['required', 'integer', Rule::exists('pgsql.workflow.estado', 'id')],
        ]);

        if ($request->get('id') === null) {
            $transicion = new Etapa();
        } else {
            $transicion = Etapa::find($request->get('id'));
        }

        $transicion->codigo = $request->get('codigo');
        $transicion->nombre = $request->get('nombre');
        $transicion->flujo_id = $request->get('flujo_id');
        $transicion->etapa_origen_id = $request->get('etapa_origen_id');
        $transicion->etapa_destino_id = $request->get('etapa_destino_id');
        $transicion->estado_origen_id = $request->get('estado_origen_id');
        $transicion->estado_destino_id = $request->get('estado_destino_id');

        $transicion->save();

        $item = Transicion::with('creator', 'updater', 'flujo', 'etapaOrigen', 'etapaDestino', 'estadoOrigen', 'estadoDestino')->find($transicion->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {

        $delete = Transicion::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
