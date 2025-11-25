<?php

namespace App\Http\Controllers\Academico;

use App\Http\Controllers\Controller;
use App\Models\Academico\CarreraSede;
use App\Models\Academico\Imparte;
use App\Models\Academico\Oferta;
use App\Models\Academico\Semestre;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
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
        foreach ($oferta as $o) {
            $ofertadas[] = $o->carreraUnidadAcademica->id;
        }
        $items = [];
        foreach ($carreras as $c) {

            $unidades = $c->unidadesAcademicas()->orderBy('semestre')->get();

            $children = [];
            foreach ($unidades as $u) {
                $children[] = [
                    'id' => $u->id,
                    'uuid' => $u->uuid,
                    'tipo' => 'unidad',
                    'nombreCompleto' => '(' . $u->semestre . ') ' . $u->unidadAcademica->nombreCompleto,
                    'semestre' => $u->semestre,
                    'ofertada' => in_array($u->id, $ofertadas)
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

    public function getOfertaDetalle($id, $idCarreraUnidadAcademica)
    {
        $semestre = Semestre::where('uuid', $id)->first();
        $carreraUnidadAcademica = CarreraUnidadAcademica::where('uuid', $idCarreraUnidadAcademica)->first();

        $oferta = Oferta::where('semestre_id', $semestre->id)
            ->where('carrera_unidad_academica_id', $carreraUnidadAcademica->id)
            ->first();

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
                'docente_id' => null, //$i->docente_id,
                'responsables' => $i->docentes()->with('persona')->get(), //$i->docente()->with('persona')->first(),
                'docentes' => $i->carreraSede->docentes()->with('persona')->get(),
                'editando' => false
            ];
        }

        return response()->json(['status' => 'ok', 'message' => '', 'detalleOferta' => $detalleOferta]);
    }

    public function ofertaDetalleSave(Request $request)
    {

        $imparte = Imparte::find($request->get('id'));
        $imparte->ofertada = $request->get('ofertada');
        $responsables = $request->get('responsables');
        $docentesId = [];
        foreach ($responsables as $d) {
            $docentesId[] = $d['id'];
        }

        $imparte->docentes()->sync($docentesId);

        $imparte->save();

        return response()->json(['status' => 'ok', 'message' => '_datos_guardados_']);
    }
}
