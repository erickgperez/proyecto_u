<?php

namespace App\Http\Controllers\Academica;

use App\Http\Controllers\Controller;
use App\Models\Academica\Sede;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\TipoCarrera;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SedeController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $sedes = Sede::with('creator', 'updater', 'carreras')->orderBy('nombre')->get();

        $tiposCarrera = TipoCarrera::all();

        $carreras = Carrera::orderBy('nombre')->get();

        return Inertia::render('academica/Sede', [
            'items'         => $sedes,
            'carreras'      => $carreras,
            'tiposCarrera'  => $tiposCarrera
        ]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'nullable|string|max:255'
        ]);

        if ($request->get('id') === null) {

            // Está agregando uno nuevo, verificar que no exista el código
            $sedeCheck = Sede::where('codigo', $request->get('codigo'))->first();
            if ($sedeCheck === null) {
                $sede = new Sede();
            } else {
                return response()->json(['status' => 'error', 'message' => 'sede._codigo_ya existe_']);
            }
        } else {
            // Verificar que el nuevo código que ponga no esté utilizado por otro registro
            $sedeCheck = Sede::where('codigo', $request->get('codigo'))
                ->where('id', '!=', $request->get('id'))
                ->first();

            if ($sedeCheck === null) {
                $sede = Sede::find($request->get('id'));
            } else {
                return response()->json(['status' => 'error', 'message' => 'tipoCarrera._codigo_ya existe_']);
            }
        }

        $sede->codigo = $request->get('codigo');
        $sede->nombre = $request->get('nombre');
        $sede->save();

        $sede->carreras()->sync($request->get('carreras') ?? []);

        $item = $sede->toArray();

        $item = Sede::with('creator', 'updater', 'carreras')->find($sede->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {
        $delete = Sede::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
