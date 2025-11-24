<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Estado;
use App\Models\Academico\TipoCurso;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EstadoController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tipos = Estado::with('creator', 'updater', 'uso')->orderBy('codigo')->get();

        return Inertia::render('academico/Estado', ['items' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:20',
            'descripcion' => 'required|string|max:100'
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $estadoCheck = Estado::where('codigo', $request->get('codigo'))->first();
            if ($estadoCheck === null) {
                $estado = new TipoCurso();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCurso._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $estadoCheck = Estado::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($estadoCheck === null) {
                $estado = Estado::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCurso._codigo_ya existe_']);
            }
        }

        $estado->codigo = $request->get('codigo');
        $estado->descripcion = $request->get('descripcion');

        $estado->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = Estado::with('creator', 'updater', 'uso')->find($estado->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($uuid)
    {
        $delete = Estado::where('uuid', $uuid)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $uuid]);
        }
    }
}
