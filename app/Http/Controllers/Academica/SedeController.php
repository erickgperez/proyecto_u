<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\Academica\Sede;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Ingreso\Convocatoria;
use App\Models\Municipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SedeController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $sedes = Sede::orderBy('nombre')->get();
        $distritos = Distrito::orderBy('descripcion')->get();
        $departamentos = Departamento::orderBy('descripcion')->get();
        $municipios = Municipio::orderBy('descripcion')->get();

        $resp = [];
        foreach ($sedes as $row) {
            $items = $row->toArray();
            $items['created_by'] = $row->creator;
            $items['updated_by'] = $row->updater;
            $items['distrito'] = $row->distrito->descripcion;
            $items['departamento_id'] = $row->distrito->municipio->departamento_id;
            $items['municipio_id'] = $row->distrito->municipio_id;

            $resp[] = $items;
        }

        return Inertia::render('academica/Sede', ['items' => $resp, 'distritos' => $distritos, 'departamentos' => $departamentos,  'municipios' => $municipios]);
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
