<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\FormaImparte;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FormaImparteController extends Controller
{
    /**
     *
     */
    public function index(): Response
    {

        $tipos = FormaImparte::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('academico/FormaImparte', ['items' => $tipos]);
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
            $formaImparteCheck = FormaImparte::where('codigo', $request->get('codigo'))->first();
            if ($formaImparteCheck === null) {
                $formaImparte = new FormaImparte();
            } else {
                return response()->json(['status' => 'error', 'message' => 'formaImparte._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $formaImparteCheck = FormaImparte::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($formaImparteCheck === null) {
                $formaImparte = FormaImparte::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'formaImparte._codigo_ya existe_']);
            }
        }

        $formaImparte->codigo = $request->get('codigo');
        $formaImparte->descripcion = $request->get('descripcion');

        $formaImparte->save();

        //Obtener la información de las relaciones del item recién creado/actualizado
        $item = FormaImparte::with('creator', 'updater')->find($formaImparte->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {
        $delete = FormaImparte::where('id', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
