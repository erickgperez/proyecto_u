<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\Academica\Sede;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Municipio;
use App\Models\PlanEstudio\Carrera;
use Illuminate\Http\Request;
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

        $sedes = Sede::with('distrito', 'creator', 'updater', 'carreras')->orderBy('nombre')->get();

        $distritos = Distrito::orderBy('descripcion')->get();
        $departamentos = Departamento::orderBy('descripcion')->get();
        $municipios = Municipio::orderBy('descripcion')->get();
        $carreras = Carrera::orderBy('nombre')->get();

        $items = [];
        foreach ($sedes as $row) {
            $item = $row->toArray();
            $item['distrito_'] = $row->distrito->nombreCompleto;
            $item['departamento_id'] = $row->distrito->municipio->departamento_id;
            $item['municipio_id'] = $row->distrito->municipio_id;

            $items[] = $item;
        }

        return Inertia::render('academica/Sede', [
            'items'         => $items,
            'distritos'     => $distritos,
            'departamentos' => $departamentos,
            'municipios'    => $municipios,
            'carreras'      =>  $carreras
        ]);
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

            // Está agregando uno nuevo, verificar que no exista el código
            $sedeCheck = Sede::where('codigo', $request->get('codigo'))->first();
            if ($sedeCheck === null) {
                $sede = new Sede();
            } else {
                return response()->json(['status' => 'error', 'message' => 'sede._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $sedeCheck = Sede::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($sedeCheck === null) {
                $sede = Sede::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCarrera._codigo_ya existe_']);
            }
        }

        $distrito = Distrito::find($request->get('distrito_id'));
        $sede->codigo = $request->get('codigo');
        $sede->nombre = $request->get('nombre');
        $sede->distrito()->associate($distrito);
        $sede->carreras()->sync($request->get('carreras') ?? []);


        $sede->save();

        $item = $sede->toArray();

        $item = Sede::with('distrito', 'creator', 'updater', 'carreras')->find($sede->id);
        $item['distrito_'] = $sede->distrito->nombreCompleto;
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
