<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Etapa;
use App\Models\Workflow\Flujo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class EtapaController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $etapas = Etapa::with('creator', 'updater', 'flujo')->orderBy('codigo')->get();
        $flujos = Flujo::orderBy('codigo')->where('activo', true)->get();

        return Inertia::render('administracion/Etapa', ['items' => $etapas, 'flujos' => $flujos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'nombre' => 'required|string|max:255',
            'indicaciones' => 'nullable|string',
            'flujo_id' => ['required', 'integer', Rule::exists('pgsql.workflow.flujo', 'id')],
        ]);

        if ($request->get('id') === null) {
            $etapa = new Etapa();
        } else {
            $etapa = Etapa::find($request->get('id'));
        }

        $etapa->codigo = $request->get('codigo');
        $etapa->nombre = $request->get('nombre');
        $etapa->indicaciones = $request->get('indicaciones');
        $etapa->flujo_id = $request->get('flujo_id');

        $etapa->save();

        $item = Etapa::with('creator', 'updater', 'flujo')->find($etapa->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {

        $delete = Etapa::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
