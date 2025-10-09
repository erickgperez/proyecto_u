<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Calendarizacion;
use App\Models\Ingreso\Convocatoria;
use App\Models\Permisos;
use App\Models\Persona;
use App\Models\Sexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PermisosController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $permisos = Permisos::orderBy('name')->get();

        return Inertia::render('administracion/Permisos', ['items' => $permisos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->get('id') === null) {
            $permiso = new Permisos();
        } else {
            $permiso = Permisos::find($request->get('id'));
        }

        $permiso->name = $request->get('name');

        $permiso->save();

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $permiso]);
    }

    public function delete(int $id)
    {

        $delete = Permisos::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
