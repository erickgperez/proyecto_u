<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\TipoUnidadAcademica;
use App\Models\PlanEstudio\UnidadAcademica;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UnidadAcademicaController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $unidadesAcademica = UnidadAcademica::with('creator', 'updater', 'tipo')->orderBy('nombre')->get();
        $tipos = TipoUnidadAcademica::orderBy('codigo')->get();

        return Inertia::render('academico/UnidadAcademica', ['items' => $unidadesAcademica, 'tipos' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string',
            'creditos' => 'required|numeric|min:0',
            'tipo_unidad_academica_id' => 'required',
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $unidadAcademicaCheck = UnidadAcademica::where('codigo', $request->get('codigo'))->first();
            if ($unidadAcademicaCheck === null) {
                $unidadAcademica = new UnidadAcademica();
            } else {
                return response()->json(['status' => 'error', 'message' => 'unidadAcademica._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $unidadAcademicaCheck = UnidadAcademica::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($unidadAcademicaCheck === null) {
                $unidadAcademica = UnidadAcademica::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'unidadAcademica._codigo_ya existe_']);
            }
        }

        $unidadAcademica->codigo = $request->get('codigo');
        $unidadAcademica->nombre = $request->get('nombre');
        $unidadAcademica->creditos = $request->get('creditos');

        $tipo = $request->get('tipo_unidad_academica_id');
        $unidadAcademica->tipo_unidad_academica_id = $tipo['id'];

        $unidadAcademica->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = UnidadAcademica::with('creator', 'updater', 'tipo')->find($unidadAcademica->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = UnidadAcademica::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
