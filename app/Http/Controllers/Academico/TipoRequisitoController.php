<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\TipoRequisito;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TipoRequisitoController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tipos = TipoRequisito::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('academico/TipoRequisito', ['items' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:50',
            'descripcion' => 'required|string|max:150'
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $tipoRequisitoCheck = TipoRequisito::where('codigo', $request->get('codigo'))->first();
            if ($tipoRequisitoCheck === null) {
                $tipo = new TipoRequisito();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoRequisito._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoRequisitoCheck = TipoRequisito::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoRequisitoCheck === null) {
                $tipo = TipoRequisito::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoRequisito._codigo_ya existe_']);
            }
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = TipoRequisito::with('creator', 'updater')->find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = TipoRequisito::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
