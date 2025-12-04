<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Academico\Asignado;
use App\Models\Academico\Docente;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;


class DocenteController extends Controller
{

    public function data($id, Request $request)
    {

        $persona = Persona::where('uuid', $id)->first();

        $docente = Docente::with('carrerasSedes')
            ->where('persona_id', $persona->id)->first();

        return response()->json(['status' => 'ok', 'message' => '', 'docente' => $docente]);
    }

    public function asignacionSave($id, Request $request)
    {

        $persona = Persona::find($id);
        $docente = $persona->docente;

        if (!$docente) {
            //No existe el registro de docente, hacerlo
            $docente = new Docente();
            $docente->persona()->associate($persona);

            $docente->save();
        }

        $docente->carrerasSedes()->sync($request->get('carreras_sedes') ?? []);

        //Si tiene asignación principal
        if ($request->get('carrera_sede_principal_id')) {
            $asignado = Asignado::where('docente_id', $docente->id)
                ->where('carrera_sede_id', $request->get('carrera_sede_principal_id'))
                ->first();
            $asignado->principal = true;
            $asignado->save();
        }


        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }

    public function carga($uuidSemestre, $uuidDocente)
    {
        $semestre = Semestre::where('uuid', $uuidSemestre)->first();
        $docente = Docente::where('uuid', $uuidDocente)->first();

        $carrerasSede = [];
        foreach ($docente->carrerasSedes as $cs) {
            $carrerasSede[] = $cs->id;
        }
        $ofertaCarreraSede = Imparte::with([
            'oferta' => function ($query) use ($semestre) {
                $query->with('carreraUnidadAcademica')
                    ->where('semestre_id', $semestre->id);
            },
            'docentes' => function ($query) use ($docente) {
                $query->where('docente.id', $docente->id);
            }
        ])
            ->where('ofertada', true)
            //->whereIn('carrera_sede_id', $carrerasSede)
            ->get();

        $ofertaCarreraSede_ = [];
        foreach ($ofertaCarreraSede as $o) {
            $ofertaCarreraSede_[$o->carreraSede->titulo]['unidades'][] = [
                'id' => $o->id,
                'title' => '(' . $o->oferta->carreraUnidadAcademica->semestre . ') ' . $o->oferta->carreraUnidadAcademica->unidadAcademica->nombre,
                'carreraSede' => $o->carreraSede->titulo,
                'unidad' => $o->oferta->carreraUnidadAcademica->unidadAcademica->nombre
            ];
            $ofertaCarreraSede_[$o->carreraSede->titulo]['id'] = 'cs' . $o->carreraSede->id;
        }
        $ofertaCarreraSedeTree = [];
        foreach ($ofertaCarreraSede_ as $k => $oo) {
            $unidades = $oo['unidades'];
            $title = array_column($unidades, 'title');
            array_multisort($title, SORT_ASC, $unidades);
            $ofertaCarreraSedeTree[] = [
                'id' => $oo['id'],
                'title' => $k,
                'children' => $unidades
            ];
        }

        $ofertaCarrera = Oferta::with([
            'carreraUnidadAcademica' => ['unidadAcademica', 'carrera'],
            'docenteTitular'
        ])
            ->where('semestre_id', $semestre->id)
            ->get();

        $ofertaCarrera_ = [];
        foreach ($ofertaCarrera as $o) {
            $ofertaCarrera_[$o->carreraUnidadAcademica->carrera->nombreCompleto]['unidades'][] = [
                'id' => $o->id,
                'title' => '(' . $o->carreraUnidadAcademica->semestre . ') ' . $o->carreraUnidadAcademica->unidadAcademica->nombre,
                'carrera' => $o->carreraUnidadAcademica->carrera->nombreCompleto,
                'unidad' => $o->carreraUnidadAcademica->unidadAcademica->nombre
            ];
            $ofertaCarrera_[$o->carreraUnidadAcademica->carrera->nombreCompleto]['id'] = 'cs' . $o->carreraUnidadAcademica->id;
        }

        $ofertaCarreraTree = [];
        foreach ($ofertaCarrera_ as $k => $oo) {
            $unidades = $oo['unidades'];
            $title = array_column($unidades, 'title');
            array_multisort($title, SORT_ASC, $unidades);
            $ofertaCarreraTree[] = [
                'id' => $oo['id'],
                'title' => $k,
                'children' => $unidades
            ];
        }

        $cargaAsociado = $docente->imparte()
            ->select('imparte.*')
            ->join('academico.oferta as oferta', 'oferta.id', '=', 'imparte.oferta_id')
            ->where('oferta.semestre_id', $semestre->id)
            ->get();
        $cargaTitular = $docente->cargaTitular()->where('semestre_id', $semestre->id)->get();

        return response()->json([
            'status' => 'ok',
            'ofertaCarreraSede' => $ofertaCarreraSedeTree,
            'ofertaCarrera' => $ofertaCarreraTree,
            'cargaAsociado' => $cargaAsociado,
            'cargaTitular' => $cargaTitular
        ]);
    }

    public function cargaSave($uuid, Request $request)
    {

        $docente = Docente::where('uuid', $uuid)->first();
        $docente->imparte()->sync($request->get('cargaAsociado') ?? []);
        $user = User::select('users.*')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('persona_usuario', 'persona_usuario.usuario_id', '=', 'users.id')
            ->where('persona_usuario.persona_id', $docente->persona_id)
            ->where('roles.name', 'docente')
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->first();

        // Quitar el rol de docente-titular
        $user->removeRole('docente-titular');

        //Desasociar todos los registros que tenga como titular
        foreach ($docente->cargaTitular as $oferta) {
            $oferta->docenteTitular()->dissociate();
            $oferta->save();
        }

        //Volver a asociar los registros nuevos
        foreach ($request->get('cargaTitular') ?? [] as $ct) {
            $oferta = Oferta::find($ct);
            $oferta->docenteTitular()->associate($docente);

            $oferta->save();
        }

        //Volverlo a asignar solo si tiene cargar titular
        if ($docente->cargaTitular()->count() > 0) {
            $user->assignRole('docente-titular');
        }

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }

    public function getDocenteData($uuid)
    {
        $persona = Persona::where('uuid', $uuid)->first();
        $docente = $persona->docente()
            ->with([
                'persona' => ['sexo'],
                'cargaTitular' => ['semestre', 'carreraUnidadAcademica' => ['unidadAcademica', 'carrera']],
                'imparte' => ['carreraSede', 'oferta' => ['semestre', 'carreraUnidadAcademica' => ['unidadAcademica', 'carrera']]]
            ])
            ->first();
        return response()->json(['status' => 'ok', 'docente' => $docente]);
    }
}
