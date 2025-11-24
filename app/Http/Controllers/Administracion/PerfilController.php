<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\DatosContacto;
use App\Models\Documento\TipoDocumento;
use App\Models\Persona;
use App\Models\Sexo;
use App\Models\User;
use App\Services\DistritoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PerfilController extends Controller
{
    protected $distritoService;

    public function __construct(DistritoService $distritoService)
    {
        $this->distritoService = $distritoService;
    }

    /**
     *
     */
    public function index($perfil, $perfiles): Response
    {

        $sexos = Sexo::all();
        $tiposDocumento = TipoDocumento::orderBy('codigo')->get();
        $distritosTree = $this->distritoService->distritosLikeTree();

        return Inertia::render('administracion/Perfil', [
            'items' => $perfiles,
            'perfil' => $perfil,
            'sexos' => $sexos,
            'distritosTree' => $distritosTree,
            'tiposDocumento' => $tiposDocumento,
        ]);
    }

    public function indexAspirante(): Response
    {
        $aspirantes = $this->personaBase();
        $aspirantes = $aspirantes->join('ingreso.aspirante as aspirante', 'persona.id', '=', 'aspirante.persona_id')
            ->get();

        return $this->index('aspirante', $aspirantes);
    }

    protected function personaBase()
    {
        return Persona::with(['sexo', 'creator', 'updater', 'datosContacto' => ['distritoResidencia'], 'usuarios']);
    }

    public function personaInfo($id, Request $request)
    {

        $persona = Persona::with(['sexo', 'creator', 'updater', 'datosContacto' => ['distritoResidencia']])->where('uuid', $id)->first();

        $distritosTree = $this->distritoService->distritosLikeTree();

        return response()->json(['status' => 'ok', 'message' => '', 'persona' => $persona, 'distritosTree' => $distritosTree]);
    }

    public function save(Request $request)
    {
        // Aunque se ha validado del lado del cliente, validar aquí también
        $request->validate([
            'primer_nombre' => 'required|string|max:100',
            'segundo_nombre' => 'nullable|string|max:100',
            'tercer_nombre' => 'nullable|string|max:100',
            'primer_apellido' => 'required|string|max:100',
            'segundo_apellido' => 'nullable|string|max:100',
            'tercer_apellido' => 'nullable|string|max:100',
            'fecha_nacimiento' => 'nullable|date',
            'perfil' => 'nullable|string|max:100',
            'sexo_id' => ['nullable', 'integer', Rule::exists('sexo', 'id')],
        ]);

        if ($request->get('id') === null) {
            $persona = new Persona();
        } else {
            $persona = Persona::find($request->get('id'));
        }

        $persona->primer_nombre = $request->get('primer_nombre');
        $persona->segundo_nombre = $request->get('segundo_nombre');
        $persona->tercer_nombre = $request->get('tercer_nombre');
        $persona->primer_apellido = $request->get('primer_apellido');
        $persona->segundo_apellido = $request->get('segundo_apellido');
        $persona->tercer_apellido = $request->get('tercer_apellido');
        $persona->fecha_nacimiento = $request->get('fecha_nacimiento');
        $persona->sexo_id = $request->get('sexo_id');

        $persona->save();
        if ($request->get('id') === null) {
            $userCheck = User::where('codigo', $request->get('codigo'))->first();
            if ($userCheck !== null) {
                return response()->json(['status' => 'error', 'message' => 'perfil._correo_cuenta existe_']);
            }
            $usuario = new User();
            $usuario->name = $persona->primer_nombre . ' ' . $persona->primer_apellido;
            $usuario->email = $request->get('email_cuenta_usuario');
            $usuario->assignRole('aspirante');
            $usuario->save();

            $persona->usuarios()->syncWithoutDetaching([$usuario->id]);
        }

        $persona_ = $this->personaBase();
        if ($request->get('perfil') === 'aspirante') {
            $persona_->join('ingreso.aspirante as aspirante', 'persona.id', '=', 'aspirante.persona_id');
        }
        $personaData = $persona_->find($persona->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $personaData]);
    }

    public function datosContactoSave($id, Request $request)
    {
        $persona = Persona::where('uuid', $id)->first();
        $datosContacto = $persona->datosContacto;

        if (!$datosContacto) {
            $datosContacto = new DatosContacto();
        }

        $campos = [
            'email_principal',
            'email_alternativo',
            'direccion_residencia',
            'residencia_distrito_id',
            'telefono_residencia',
            'direccion_trabajo',
            'telefono_trabajo',
            'telefono_personal',
            'telefono_personal_alternativo'
        ];
        foreach ($campos as $c) {
            $datosContacto->{$c} = $request->get($c);
        }
        $datosContacto->persona()->associate($persona);
        $datosContacto->save();


        $personaData = Persona::with(['sexo', 'creator', 'updater', 'datosContacto' => ['distritoResidencia']])->find($persona->id);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $personaData]);
    }

    public function delete($id)
    {

        $delete = Persona::where('uuid', $id)->first()->delete();

        if ($delete == 0) {
            return response()->json(['status' => 'error', 'message' => '_no_se_encontro_registro_']);
        } else {
            return response()->json(['status' => 'ok', 'message' => $id]);
        }
    }
}
