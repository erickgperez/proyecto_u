<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
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

        $items = [];
        foreach ($tiposCarrera as $row) {
            $item = $row->toArray();
            $item['created_by'] = $row->creator;
            $item['updated_by'] = $row->updater;
            $item['grado'] = $row->grado;
            $items[] = $item;
        }

        return Inertia::render('academica/TipoCarrera', ['items' => $items, 'grados' => $grados]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:20',
            'descripcion' => 'required|string|max:255',
            'grado_id' => ['nullable', 'integer', Rule::exists('pgsql.plan_estudio.grado', 'id')],
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $tipoCarreraCheck = TipoCarrera::where('codigo', $request->get('codigo'))->first();
            if ($tipoCarreraCheck === null) {
                $tipoCarrera = new TipoCarrera();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCarrera._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoCarreraCheck = TipoCarrera::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoCarreraCheck === null) {
                $tipoCarrera = TipoCarrera::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCarrera._codigo_ya existe_']);
            }
        }

        $grado = Grado::find($request->get('grado_id'));

        $tipoCarrera->codigo = $request->get('codigo');
        $tipoCarrera->descripcion = $request->get('descripcion');
        $tipoCarrera->grado()->associate($grado);

        $tipoCarrera->save();

        $item = $tipoCarrera->toArray();
        $item['created_by'] = $tipoCarrera->creator;
        $item['updated_by'] = $tipoCarrera->updater;
        $item['grado'] = $tipoCarrera->grado;

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = TipoCarrera::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
