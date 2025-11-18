<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\Semestre;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SemestreController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $semestres = Semestre::with('creator', 'updater', 'calendario')->orderBy('codigo')->get();

        return Inertia::render('academico/Semestre', ['items' => $semestres]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:50',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $semestreCheck = Semestre::where('codigo', $request->get('codigo'))->first();
            if ($semestreCheck === null) {
                $semestre = new Semestre();
            } else {
                return response()->json(['status' => 'error', 'message' => 'semestre._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $semestreCheck = Semestre::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($semestreCheck === null) {
                $semestre = Semestre::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'semestre._codigo_ya existe_']);
            }
        }

        $semestre->codigo = $request->get('codigo');
        $semestre->descripcion = $request->get('descripcion');
        $semestre->fecha_inicio = $request->get('fecha_inicio');
        $semestre->fecha_fin = $request->get('fecha_fin');

        $semestre->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = Semestre::with('creator', 'updater', 'calendario')->find($semestre->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = Semestre::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
