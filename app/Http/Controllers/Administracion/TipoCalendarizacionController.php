<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\TipoCalendarizacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TipoCalendarizacionController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $tipos = TipoCalendarizacion::with('creator', 'updater')->orderBy('codigo')->get();

        return Inertia::render('administracion/TipoCalendarizacion', ['items' => $tipos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $tipoRequisitoCheck = TipoCalendarizacion::where('codigo', $request->get('codigo'))->first();
            if ($tipoRequisitoCheck === null) {
                $tipo = new TipoCalendarizacion();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCalendarizacion._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoRequisitoCheck = TipoCalendarizacion::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoRequisitoCheck === null) {
                $tipo = TipoCalendarizacion::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCalendarizacion._codigo_ya existe_']);
            }
        }

        $tipo->codigo = $request->get('codigo');
        $tipo->descripcion = $request->get('descripcion');

        $tipo->save();

        $item = TipoCalendarizacion::with('creator', 'updater')->find($tipo->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {

        $delete = TipoCalendarizacion::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
