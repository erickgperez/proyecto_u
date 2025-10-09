<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UsuarioController extends Controller
{
    /**
     *
     */
    public function index(Request $request): Response
    {

        $usuarios = User::with('personas', 'roles')->get();
        $personas = Persona::all();
        $roles = Rol::all();

        return Inertia::render('administracion/Usuario', ['items' => $usuarios, 'personas' => $personas, 'roles' => $roles]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'unique:users,email,' . $request->get('id') . '|required|email|max:255',
            'persona_id' => ['nullable', 'integer', Rule::exists('pgsql.public.persona', 'id')],
        ]);

        if ($request->get('id') === null) {
            $usuario = new User();
        } else {
            $usuario = User::find($request->get('id'));
        }

        $usuario->name = $request->get('name');
        $usuario->email = $request->get('email');

        $usuario->personas()->sync([$request->get('persona_id') ?? '']);

        $usuario->syncRoles($request->get('roles'));

        $usuario->save();

        $usuarioData = User::with('personas', 'roles')->find($usuario->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $usuarioData]);
    }

    public function delete(int $id)
    {

        $delete = User::destroy($id);

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
