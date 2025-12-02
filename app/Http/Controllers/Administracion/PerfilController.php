<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Academico\Docente;
use App\Models\DatosContacto;
use App\Models\Documento\TipoDocumento;
use App\Models\Persona;
use App\Models\Sexo;
use App\Models\User;
use App\Services\DistritoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use App\Models\Ingreso\Convocatoria;
use Illuminate\Support\Facades\Auth;

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
        $tiposDocumento = TipoDocumento::with('roles')->orderBy('codigo')->get();

        $tipos = [];
        foreach ($tiposDocumento as $td) {
            foreach ($td->roles as $rol) {
                if ($rol->guard_name == 'web' && $rol->name == $perfil) {
                    $tipos[] = $td;
                    break;
                }
            }
        }

        $distritosTree = $this->distritoService->distritosLikeTree();

        return Inertia::render('administracion/Perfil', [
            'items' => $perfiles,
            'perfil' => $perfil,
            'sexos' => $sexos,
            'distritosTree' => $distritosTree,
            'tiposDocumento' => $tipos,
        ]);
    }

    public function indexAspirante($convocatoriaUUID = null): Response
    {
        if ($convocatoriaUUID != null) {
            $convocatoria = Convocatoria::where('uuid', $convocatoriaUUID)->first();
        } else {
            //obtener la primer convocatoria
            $convocatoria = Convocatoria::orderBy('created_at', 'desc')->first();
        }
        $aspirantes = $this->getAspiranteBase($convocatoria)->get();

        return $this->index('aspirante', $aspirantes);
    }

    protected function getAspiranteBase($convocatoria = null)
    {

        $convocatoriaId = $convocatoria ? $convocatoria->id : null;
        $aspiranteQuery =  Persona::with([
            'sexo',
            'creator',
            'updater',
            'datosContacto' => ['distritoResidencia'],
            'aspirante',
            'usuarios' => function ($query) {
                $query->join('model_has_roles as roles', 'users.id', '=', 'roles.model_id')
                    ->join('roles as rol', 'roles.role_id', '=', 'rol.id')
                    ->where('roles.model_type', 'App\Models\User')
                    ->where('rol.name', 'aspirante')
                    ->orWhere('rol.name', 'estudiante');
            }
        ])->select('persona.*', 'convocatoria_aspirante.seleccionado')
            ->join('ingreso.aspirante as aspirante', 'persona.id', '=', 'aspirante.persona_id')
            ->join('ingreso.convocatoria_aspirante as convocatoria_aspirante', 'aspirante.id', '=', 'convocatoria_aspirante.aspirante_id');
        if ($convocatoria) {
            $aspiranteQuery->where('convocatoria_aspirante.convocatoria_id', $convocatoriaId);
        }

        return $aspiranteQuery;
    }

    protected function getDocenteBase()
    {
        return Persona::with([
            'sexo',
            'creator',
            'updater',
            'datosContacto' => ['distritoResidencia'],
            'docente' => ['carrerasSedes' => ['carrera', 'sede']],
            'usuarios' => function ($query) {
                $query->join('model_has_roles as roles', 'users.id', '=', 'roles.model_id')
                    ->join('roles as rol', 'roles.role_id', '=', 'rol.id')
                    ->where('roles.model_type', 'App\Models\User')
                    ->where('rol.name', 'docente');
            }
        ])->select('persona.*')
            ->join('academico.docente as docente', 'persona.id', '=', 'docente.persona_id');
    }

    public function indexEstudiante(): Response
    {
        $estudiantes = $this->getEstudianteBase()->get();

        return $this->index('estudiante', $estudiantes);
    }


    protected function getEstudianteBase()
    {
        return Persona::with([
            'sexo',
            'creator',
            'updater',
            'datosContacto' => ['distritoResidencia'],
            'estudiante',
            'usuarios' => function ($query) {
                $query->join('model_has_roles as roles', 'users.id', '=', 'roles.model_id')
                    ->join('roles as rol', 'roles.role_id', '=', 'rol.id')
                    ->where('roles.model_type', 'App\Models\User')
                    ->where('rol.name', 'estudiante');
            }
        ])->select('persona.*')
            ->join('academico.estudiante as estudiante', 'persona.id', '=', 'estudiante.persona_id');
    }

    public function indexDocente(): Response
    {

        $docentes = $this->getDocenteBase()->get();

        return $this->index('docente', $docentes);
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

        if (Auth::user()->can('ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DATOS-PERSONALES') || Auth::user()->hasRole('super-admin')) {
            $persona->permitir_editar = $request->get('permitir_editar') ?? false;
        } else {
            $persona->permitir_editar = false;
        }

        $persona->sexo_id = $request->get('sexo_id');

        $persona->save();
        if ($request->get('email_cuenta_usuario') !== null) {
            $mandarEnlace = false;
            if ($request->get('id') === null) {
                $usuario = $this->crearUsuario($persona, $request->get('perfil'), $request->get('email_cuenta_usuario'));

                $mandarEnlace = true;
            } else {
                $usuarios = $persona->usuarios;
                //Verificar si el usuario ya existe asociado a la persona
                $usuario = null;
                foreach ($usuarios as $user) {
                    //Verificar si ya existe con el rol requerido
                    if ($user->hasRole($request->get('perfil'))) {
                        $usuario = $user;
                        break;
                    }
                }
                //Si no existe, crearlo
                if ($usuario === null) {
                    $usuario = $this->crearUsuario($persona, $request->get('perfil'), $request->get('email_cuenta_usuario'));
                    $mandarEnlace = true;
                } else {
                    //si ya existe y es una dirección de correo diferente, cambiar la dirección de correo
                    if ($usuario->email !== $request->get('email_cuenta_usuario')) {
                        $usuario->email = $request->get('email_cuenta_usuario');
                        $usuario->save();

                        $mandarEnlace = true;
                    }
                }
            }

            if ($mandarEnlace) {
                Password::sendResetLink(
                    ['email' => $usuario->email]
                );
            }
        }

        if ($request->get('perfil') === 'aspirante') {
            $personaData = $this->getAspiranteBase()->find($persona->id);
        } elseif ($request->get('perfil') === 'docente') {
            $personaData = $this->getDocenteBase()->find($persona->id);
        } elseif ($request->get('perfil') === 'estudiante') {
            $personaData = $this->getEstudianteBase()->find($persona->id);
        }

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'item' => $personaData]);
    }

    protected function crearUsuario($persona, $perfil, $email_cuenta)
    {
        $usuario = new User();
        $usuario->name = $persona->primer_nombre . ' ' . $persona->primer_apellido;
        $usuario->email = $email_cuenta;
        $usuario->email_verified_at = new \DateTime();
        $usuario->password = Str::password();

        if ($perfil === 'aspirante') {
            $usuario->assignRole('aspirante');
        } elseif ($perfil === 'docente') {
            $usuario->assignRole('docente');

            //Crear registro en docente
            $docente = new Docente();
            $docente->persona()->associate($persona);

            $docente->save();
        }
        $usuario->save();

        $persona->usuarios()->syncWithoutDetaching([$usuario->id]);

        return $usuario;
    }

    public function datosContactoSave($id, Request $request)
    {
        $persona = Persona::where('uuid', $id)->first();
        $datosContacto = $persona->datosContacto;

        if (!$datosContacto) {
            $datosContacto = new DatosContacto();
        }

        $campos = [
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
        if (Auth::user()->can('ADMINISTRACION_PERFIL_AUTORIZAR_EDICION_DATOS-CONTACTO') || Auth::user()->hasRole('super-admin')) {
            $datosContacto->permitir_editar = $request->get('permitir_editar') ?? false;
        } else {
            $datosContacto->permitir_editar = false;
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
