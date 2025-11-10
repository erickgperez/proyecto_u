<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\TipoUnidadAcademica;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TipoUnidadAcademicaController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tipos = TipoUnidadAcademica::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('academica/TipoUnidadAcademica', ['items' => $tipos]);
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
            $tipoUnidadAcademicaCheck = TipoUnidadAcademica::where('codigo', $request->get('codigo'))->first();
            if ($tipoUnidadAcademicaCheck === null) {
                $tipo = new TipoUnidadAcademica();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoUnidadAcademica._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoUnidadAcademicaCheck = TipoUnidadAcademica::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoUnidadAcademicaCheck === null) {
                $tipo = TipoUnidadAcademica::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoUnidadAcademica._codigo_ya existe_']);
            }
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = TipoUnidadAcademica::with('creator', 'updater')->find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = TipoUnidadAcademica::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
