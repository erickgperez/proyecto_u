<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\Area;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AreaController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $areas = Area::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('academico/Area', ['items' => $areas]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255'
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $areaCheck = Area::where('codigo', $request->get('codigo'))->first();
            if ($areaCheck === null) {
                $area = new Area();
            } else {
                return response()->json(['status' => 'error', 'message' => 'area._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $areaCheck = Area::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($areaCheck === null) {
                $area = Area::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'area._codigo_ya existe_']);
            }
        }

        $area->codigo = $request->get('codigo');
        $area->descripcion = $request->get('descripcion');

        $area->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = Area::with('creator', 'updater')->find($area->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = Area::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
