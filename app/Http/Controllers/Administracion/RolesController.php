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

        $rolesA = [];

        $per = [];
        foreach ($permisos as $r) {
            $per[] = $r->name . '***' . $r->id;
        }
        //$rolesA = array_values($rolesA);

        $rolesA = $this->buildMenuTree($per);

        //foreach ($rolesA as $)



        return Inertia::render('administracion/Roles', ['items' => $roles, 'permisos_' => $rolesA]);
    }

    function buildMenuTree(array $keys)
    {
        $tree = [];

        foreach ($keys as $key) {
            // Divide la clave en niveles
            list($clave, $id) = explode('***', $key);
            $parts = explode('_', $clave);
            $current = &$tree; // Referencia para ir bajando en niveles

            foreach ($parts as $i => $part) {
                // Si no existe el nodo actual, lo creamos
                if (!isset($current[$part])) {
                    $current[$part] = [
                        'id' => $clave,
                        'title' => $clave,
                        'children' => []
                    ];
                }

                // Si estamos en el último nivel, no creamos más "children"
                if ($i === count($parts) - 1) {
                    unset($current[$part]['children']);
                } else {
                    $current = &$current[$part]['children']; // bajar un nivel
                }
            }
        }

        // Reindexar arrays para que 'children' sea numérico
        $normalize = function (&$nodes) use (&$normalize) {
            $nodes = array_values($nodes);
            foreach ($nodes as &$node) {
                if (isset($node['children'])) {
                    $normalize($node['children']);
                }
            }
        };
        $normalize($tree);

        return array_values($tree);
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
