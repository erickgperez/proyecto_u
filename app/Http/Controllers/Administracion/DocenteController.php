<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Models\Academico\Asignado;
use App\Models\Academico\Docente;
use App\Models\Academico\Imparte;
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
        $oferta = Imparte::with([
            'oferta' => function ($query) use ($semestre) {
                $query->with('carreraUnidadAcademica')
                    ->where('semestre_id', $semestre->id);
            },
            'docentes' => function ($query) use ($docente) {
                $query->where('docente.id', $docente->id);
            }
        ])
            ->where('ofertada', true)
            ->whereIn('carrera_sede_id', $carrerasSede)
            ->get();

        $oferta_ = [];
        foreach ($oferta as $o) {
            $oferta_[$o->carreraSede->titulo]['unidades'][] = ['id' => $o->id, 'title' => '(' . $o->oferta->carreraUnidadAcademica->semestre . ') ' . $o->oferta->carreraUnidadAcademica->unidadAcademica->nombre];
            $oferta_[$o->carreraSede->titulo]['id'] = 'cs' . $o->carreraSede->id;
        }
        $ofertaTree = [];
        foreach ($oferta_ as $k => $oo) {
            $unidades = $oo['unidades'];
            $title = array_column($unidades, 'title');
            array_multisort($title, SORT_ASC, $unidades);
            $ofertaTree[] = [
                'id' => $oo['id'],
                'title' => $k,
                'children' => $unidades
            ];
        }


        $carga = $docente->imparte;

        return response()->json(['status' => 'ok', 'oferta' => $ofertaTree, 'carga' => $carga]);
    }

    public function cargaSave($uuid, Request $request)
    {

        $docente = Docente::where('uuid', $uuid)->first();

        $docente->imparte()->sync($request->get('carga') ?? []);

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }
}
