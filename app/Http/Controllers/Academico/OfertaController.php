<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Docente;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Models\User;
use Illuminate\Http\Request;

class OfertaController extends Controller
{

    public function index($id)
    {
        $semestre = Semestre::where('uuid', $id)->first();
        $carreras = Carrera::with(['unidadesAcademicas' => ['unidadAcademica']])
            ->whereHas('estado', function ($query) {
                $query->where('codigo', 'VIGENTE');
            })->orderBy('tipo_carrera_id')->orderBy('codigo')->orderBy('nombre')->get();

        $oferta = Oferta::with('semestre', 'carreraUnidadAcademica')
            ->where('semestre_id', $semestre->id)->get();
        $ofertadas = [];
        $titulares = [];
        foreach ($oferta as $o) {
            $ofertadas[] = $o->carreraUnidadAcademica->id;
            $titulares[$o->carreraUnidadAcademica->id] = $o->docenteTitular;
        }
        $items = [];
        foreach ($carreras as $c) {

            $unidades = $c->unidadesAcademicas()->orderBy('semestre')->get();

            $children = [];
            foreach ($unidades as $u) {
                $docenteTitular = array_key_exists($u->id, $titulares) ? $titulares[$u->id] : null;
                $children[] = [
                    'id' => $u->id,
                    'uuid' => $u->uuid,
                    'tipo' => 'unidad',
                    'nombreCompleto' => '(' . $u->semestre . ') ' . $u->unidadAcademica->nombreCompleto,
                    'semestre' => $u->semestre,
                    'ofertada' => in_array($u->id, $ofertadas),
                    'docenteTitular' => $docenteTitular
                ];
            }
            $items[] = ['id' => 'carr' . $c->id, 'tipo' => 'carrera', 'nombreCompleto' => $c->nombreCompleto . '(' . count($children) . ')', 'children' => $children];
        }
        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_', 'items' => $items, 'oferta' => $oferta]);
    }

    public function ofertar($id, $idCarreraUnidadAcademica)
    {


        $semestre = Semestre::where('uuid', $id)->first();
        $carreraUnidadAcademica = CarreraUnidadAcademica::where('uuid', $idCarreraUnidadAcademica)->first();

        $oferta = Oferta::where('semestre_id', $semestre->id)
            ->where('carrera_unidad_academica_id', $carreraUnidadAcademica->id)
            ->first();

        if ($oferta) {
            $oferta->delete();
        } else {
            $oferta = new Oferta();
            $oferta->semestre_id = $semestre->id;
            $oferta->carrera_unidad_academica_id = $carreraUnidadAcademica->id;

            $oferta->save();

            //Agregar la oferta por sede
            $carrerasSedes = CarreraSede::where('carrera_id', $carreraUnidadAcademica->carrera_id)->get();
            foreach ($carrerasSedes as $cs) {
                $imparte = new Imparte();
                $imparte->carreraSede()->associate($cs);
                $imparte->oferta()->associate($oferta);
                $imparte->ofertada = true;
                $imparte->cupo = $cs->cupo;

                $imparte->save();
            }
        }

        return response()->json(['status' => 'ok', 'message' => '']);
    }

    public function saveDocenteTitular($id, $idCarreraUnidadAcademica, $idDocente = null)
    {

        $semestre = Semestre::where('uuid', $id)->first();
        $carreraUnidadAcademica = CarreraUnidadAcademica::where('uuid', $idCarreraUnidadAcademica)->first();

        $oferta = Oferta::where('semestre_id', $semestre->id)
            ->where('carrera_unidad_academica_id', $carreraUnidadAcademica->id)
            ->first();

        if ($idDocente) {
            $docenteTitular = Docente::where('id', $idDocente)->first();
            $oferta->docenteTitular()->associate($docenteTitular);

            //verificar si el docente ya tiene el rol de docente titular
            $user = User::select('users.*')
                ->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->join('persona_usuario', 'persona_usuario.usuario_id', '=', 'users.id')
                ->where('persona_usuario.persona_id', $docenteTitular->persona_id)
                ->where('roles.name', 'docente')
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->first();

            if (!$user->hasRole('docente-titular')) {
                $user->assignRole('docente-titular');
            }
        } else {
            $docenteTitular = null;
            $oferta->docenteTitular()->dissociate();
        }

        $oferta->save();

        return response()->json(['status' => 'ok', 'message' => '', 'docenteTitular' => $docenteTitular]);
    }

    public function getOfertaDetalle($id, $idCarreraUnidadAcademica)
    {

        $semestre = Semestre::where('uuid', $id)->first();
        $carreraUnidadAcademica = CarreraUnidadAcademica::where('uuid', $idCarreraUnidadAcademica)->first();

        $oferta = Oferta::where('semestre_id', $semestre->id)
            ->where('carrera_unidad_academica_id', $carreraUnidadAcademica->id)
            ->first();
        $docentesGeneral = Docente::with('persona')->get();

        $imparte = Imparte::from('academico.imparte as i')
            ->select(['i.*'])
            ->join('academico.carrera_sede as cs', 'i.carrera_sede_id', 'cs.id')
            ->join('academico.sede as s', 'cs.sede_id', 's.id')
            ->where('oferta_id', $oferta->id)
            ->orderBy('s.nombre')->get();

        $detalleOferta = [];
        foreach ($imparte as $i) {

            $detalleOferta[] = [
                'id' => $i->id,
                'uuid' => $i->uuid,
                'sede' => $i->carreraSede->sede->nombre,
                'ofertada' => $i->ofertada,
                'cupo' => $i->cupo,
                'docente_id' => null,
                'asociados' => $i->docentes()->with('persona')->get(),
                'docentes' => $i->carreraSede->docentes()->with('persona')->get(),
                'editando' => false
            ];
        }

        return response()->json(['status' => 'ok', 'message' => '', 'detalleOferta' => $detalleOferta, 'docentes' => $docentesGeneral]);
    }

    public function ofertaDetalleSave(Request $request)
    {

        $imparte = Imparte::find($request->get('id'));
        $imparte->ofertada = $request->get('ofertada');

        $docenteTitular = $request->get('docenteTitular');

        $asociados = $request->get('asociados') ?? [];
        $docentesAsociados = [];
        foreach ($asociados as $d) {
            $docentesAsociados[] = $d['id'];
        }

        $imparte->docentes()->sync($docentesAsociados);

        $imparte->save();

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }
}
