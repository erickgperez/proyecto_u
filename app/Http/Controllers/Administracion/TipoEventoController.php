<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\TipoCalendarizacion;
use App\Models\TipoEvento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TipoEventoController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $tiposEvento = TipoEvento::with('creator', 'updater', 'tipoCalendarizacion')->orderBy('codigo')->get();
        $tiposCalendarizacion = TipoCalendarizacion::orderBy('codigo')->get();

        return Inertia::render('administracion/TipoEvento', ['items' => $tiposEvento, 'tiposCalendarizacion' => $tiposCalendarizacion]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'tipo_calendarizacion_id' => ['required', 'integer', Rule::exists('tipo_calendarizacion', 'id')],
        ]);

        if ($request->get('id') === null) {
            // Está agregando uno nuevo, verificar que no exista el código
            $tipoEventoCheck = TipoEvento::where('codigo', $request->get('codigo'))->first();
            if ($tipoEventoCheck === null) {
                $tipoEvento = new TipoEvento();
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoEvento._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $tipoEventoCheck = TipoEvento::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($tipoEventoCheck === null) {
                $tipoEvento = TipoEvento::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoEvento._codigo_ya existe_']);
            }
        }

        $tipoEvento->codigo = $request->get('codigo');
        $tipoEvento->descripcion = $request->get('descripcion');
        $tipoEvento->tipo_calendarizacion_id = $request->get('tipo_calendarizacion_id');

        $tipoEvento->save();

        $item = TipoEvento::with('creator', 'updater', 'tipoCalendarizacion')->find($tipoEvento->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {

        $delete = TipoEvento::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
