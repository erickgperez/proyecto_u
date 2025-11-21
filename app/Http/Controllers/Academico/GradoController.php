<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\PlanEstudio\Grado;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GradoController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $items = Grado::orderBy('codigo')->get();

        return Inertia::render('academico/Grado', ['items' => $items]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:15',
            'descripcion_masculino' => 'nullable|string|max:100',
            'descripcion_femenino' => 'nullable|string|max:100',
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $gradoCheck = Grado::where('codigo', $request->get('codigo'))->first();
            if ($gradoCheck === null) {
                $grado = new Grado();
            } else {
                return response()->json(['status' => 'error', 'message' => 'grado._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $gradoCheck = Grado::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($gradoCheck === null) {
                $grado = Grado::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'grado._codigo_ya existe_']);
            }
        }

        $grado->codigo = $request->get('codigo');
        $grado->descripcion_masculino = $request->get('descripcion_masculino');
        $grado->descripcion_femenino = $request->get('descripcion_femenino');

        $grado->save();

        $item = $grado->toArray();

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = Grado::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
