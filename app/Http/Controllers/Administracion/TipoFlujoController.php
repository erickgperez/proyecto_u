<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Workflow\Estado;
use App\Models\Workflow\TipoFlujo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TipoFlujoController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $tipos = TipoFlujo::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('administracion/TipoFlujo', ['items' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:150',
        ]);

        if ($request->get('id') === null) {
            $tipo = new TipoFlujo();
        } else {
            $tipo = TipoFlujo::find($request->get('id'));
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        $item = TipoFlujo::with('creator', 'updater')->find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {

        $delete = TipoFlujo::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
