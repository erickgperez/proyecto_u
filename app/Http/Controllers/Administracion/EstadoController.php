<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Estado;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EstadoController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $estados = Estado::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('administracion/Estado', ['items' => $estados]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ]);

        if ($request->get('id') === null) {
            $estado = new Estado();
        } else {
            $estado = Estado::find($request->get('id'));
        }

        $estado->codigo = $request->get('codigo');
        $estado->descripcion = $request->get('descripcion');

        $estado->save();

        $item = Estado::with('creator', 'updater')->find($estado->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {

        $delete = Estado::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
