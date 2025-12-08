<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Academico\Asignado;
use App\Models\Academico\Docente;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\Persona;
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
            'carreraSede' => ['carrera', 'sede'],
            'docentes' => function ($query) use ($docente) {
                $query->where('docente.id', $docente->id);
            }
        ])
            ->where('ofertada', true)
            ->get();


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
        ksort($ofertaCarrera_);
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

        $cargaAsociado_ = $docente->imparte()
            ->select('imparte.*')
            ->join('academico.oferta as oferta', 'oferta.id', '=', 'imparte.oferta_id')
            ->where('oferta.semestre_id', $semestre->id)
            ->get();

        $cargaAsociado = [];
        foreach ($cargaAsociado_ as $item) {
            $cargaAsociado[] = [
                'id' => $item->id,
                'carrera_sede_id' => $item->carrera_sede_id,
                'nombre' => '(Sede:' . $item->carreraSede->sede->codigo . ') (' . $item->carreraSede->carrera->nombre . ') ' . $item->oferta->carreraUnidadAcademica->unidadAcademica->nombre,
            ];
        }

        $cargaTitular = $docente->cargaTitular()
            ->where('semestre_id', $semestre->id)
            ->get();

        return response()->json([
            'status' => 'ok',
            'ofertaCarreraSede' => $ofertaCarreraSede,
            'ofertaCarrera' => $ofertaCarreraTree,
            'cargaAsociado' => $cargaAsociado,
            'cargaTitular' => $cargaTitular
        ]);
    }

    public function cargaSave($uuid, Request $request)
    {

        $docente = Docente::where('uuid', $uuid)->first();
        $docente->imparte()->sync($request->get('cargaAsociado') ?? []);

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

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }

    public function getDocenteData($uuid)
    {
        $persona = Persona::where('uuid', $uuid)->first();
        $docente = $persona->docente()
            ->with([
                'persona' => ['sexo'],
                'cargaTitular' => [
                    'semestre' => function ($query) {
                        $query->where('fecha_fin', '>=', date('Y-m-d'));
                    },
                    'carreraUnidadAcademica' => ['unidadAcademica', 'carrera']
                ],
                'imparte' => ['carreraSede', 'oferta' => ['semestre' => function ($query) {
                    $query->where('fecha_fin', '>=', date('Y-m-d'));
                }, 'carreraUnidadAcademica' => ['unidadAcademica', 'carrera']]]
            ])
            ->first();
        return response()->json(['status' => 'ok', 'docente' => $docente]);
    }
}
