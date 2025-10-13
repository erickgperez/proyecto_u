<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Flujo;
use App\Models\Workflow\TipoFlujo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class FlujoController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $flujos = Flujo::with('creator', 'updater', 'tipo')->orderBy('codigo')->get();
        $tipos = TipoFlujo::orderBy('codigo')->get();

        return Inertia::render('administracion/Flujo', ['items' => $flujos, 'tipos' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'nombre' => 'required|string|max:255',
            'activo' => 'required|boolean',
            'tipo_flujo_id' => ['required', 'integer', Rule::exists('pgsql.workflow.tipo_flujo', 'id')],
        ]);

        if ($request->get('id') === null) {
            $flujo = new Flujo();
        } else {
            $flujo = Flujo::find($request->get('id'));
        }

        $flujo->codigo = $request->get('codigo');
        $flujo->descripcion = $request->get('nombre');
        $flujo->activo = $request->get('activo');
        $flujo->tipo_flujo_id = $request->get('tipo_flujo_id');

        $flujo->save();

        $item = Flujo::with('creator', 'updater', 'tipo')->find($flujo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {

        $delete = Flujo::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
