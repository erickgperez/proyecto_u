<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\TipoCurso;
use App\Models\PlanEstudio\TipoRequisito;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TipoCursoController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tipos = TipoCurso::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('academico/TipoCurso', ['items' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:30',
            'descripcion' => 'required|string|max:150'
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $tipoCursoCheck = TipoCurso::where('codigo', $request->get('codigo'))->first();
            if ($tipoCursoCheck === null) {
                $tipo = new TipoCurso();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCurso._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoCursoCheck = TipoCurso::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoCursoCheck === null) {
                $tipo = TipoCurso::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCurso._codigo_ya existe_']);
            }
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = TipoCurso::with('creator', 'updater')->find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = TipoCurso::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
