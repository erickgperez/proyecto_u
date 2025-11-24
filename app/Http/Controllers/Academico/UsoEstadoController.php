<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\TipoCurso;
use App\Models\Academico\UsoEstado;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UsoEstadoController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tipos = UsoEstado::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('academico/UsoEstado', ['items' => $tipos]);
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
            $tipoCursoCheck = UsoEstado::where('codigo', $request->get('codigo'))->first();
            if ($tipoCursoCheck === null) {
                $tipo = new TipoCurso();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCurso._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoCursoCheck = UsoEstado::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoCursoCheck === null) {
                $tipo = UsoEstado::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCurso._codigo_ya existe_']);
            }
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = UsoEstado::with('creator', 'updater')->find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($uuid)
    {
        $delete = UsoEstado::where('uuid', $uuid)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $uuid]);
        }
    }
}
