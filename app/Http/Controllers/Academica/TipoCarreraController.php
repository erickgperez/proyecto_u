<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\Academica\Sede;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\PlanEstudio\Grado;
use App\Models\PlanEstudio\TipoCarrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TipoCarreraController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tiposCarrera = TipoCarrera::orderBy('descripcion')->get();
        $grados = Grado::orderBy('codigo')->get();

        $resp = [];
        foreach ($tiposCarrera as $row) {
            $items = $row->toArray();
            $items['created_by'] = $row->creator;
            $items['updated_by'] = $row->updater;
            $items['grado'] = $row->grado;
            $items['grado_descripciones'] = ($row->grado->descripcion_masculino ?? '') . ', ' . ($row->grado->descripcion_femenino ?? '');
            $resp[] = $items;
        }

        return Inertia::render('academica/TipoCarrera', ['items' => $resp, 'grados' => $grados]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'nullable|string|max:255',
            'distrito_id' => ['required', 'integer', Rule::exists('distrito', 'id')],
        ]);

        if ($request->get('id') === null) {
            $sede = new Sede();
        } else {
            $sede = Sede::find($request->get('id'));
        }

        $distrito = Distrito::find($request->get('distrito_id'));
        $sede->codigo = $request->get('codigo');
        $sede->nombre = $request->get('nombre');
        $sede->distrito()->associate($distrito);

        $sede->save();

        $item = $sede->toArray();
        $item['created_by'] = $sede->creator;
        $item['updated_by'] = $sede->updater;
        $item['distrito'] = $sede->distrito->descripcion;
        $item['departamento_id'] = $sede->distrito->municipio->departamento_id;
        $item['municipio_id'] = $sede->distrito->municipio_id;

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = Sede::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
