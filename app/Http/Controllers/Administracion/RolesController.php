<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Permisos;
use App\Models\Rol;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $roles = Rol::with(['permisos' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('name')->get();
        $permisos = Permisos::orderBy('name')->get();

        return Inertia::render('administracion/Roles', ['items' => $roles, 'permisos_' => $permisos]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->get('id') === null) {
            $rol = new Rol();
        } else {
            $rol = Rol::find($request->get('id'));
        }

        $rol->name = $request->get('name');

        $rol->permisos()->sync($request->get('permisos') ?? []);

        $rol->save();

        $item = Rol::with(['permisos' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->find($rol->id);


        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $item]);
    }

    public function delete(int $id)
    {

        $delete = Rol::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
