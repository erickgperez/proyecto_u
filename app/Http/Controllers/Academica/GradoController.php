<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\Grado;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GradoController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $grados = Grado::orderBy('codigo')->get();

        $resp = [];
        foreach ($grados as $row) {
            $items = $row->toArray();
            $items['created_by'] = $row->creator;
            $items['updated_by'] = $row->updater;

            $resp[] = $items;
        }

        return Inertia::render('academica/Grado', ['items' => $resp]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:15',
            'descripcion_masculino' => 'nullable|string|max:100',
            'descripcion_femenino' => 'nullable|string|max:100',
        ]);

        if ($request->get('id') === null) {
            $grado = new Grado();
        } else {
            $grado = Grado::find($request->get('id'));
        }

        $grado->codigo = $request->get('codigo');
        $grado->descripcion_masculino = $request->get('descripcion_masculino');
        $grado->descripcion_femenino = $request->get('descripcion_femenino');

        $grado->save();

        $item = $grado->toArray();

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = Grado::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
