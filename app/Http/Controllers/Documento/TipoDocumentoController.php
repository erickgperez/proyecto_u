<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Models\Documento\TipoDocumento;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TipoDocumentoController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $estados = TipoDocumento::orderBy('codigo')->get();

        return Inertia::render('documento/TipoDocumento', ['items' => $estados]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:200',
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $tipoCheck = TipoDocumento::where('codigo', $request->get('codigo'))->first();
            if ($tipoCheck === null) {
                $tipo = new TipoDocumento();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoDocumento._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoCheck = TipoDocumento::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoCheck === null) {
                $tipo = TipoDocumento::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoDocumento._codigo_ya existe_']);
            }
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        $item = TipoDocumento::find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete($id)
    {

        $delete = TipoDocumento::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
